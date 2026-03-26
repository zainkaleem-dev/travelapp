<?php

namespace App\Jobs;

use App\Services\AmadeusService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BuildFlightSearchAirlinesCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * If provided, only these airline codes will be looked up.
     *
     * @var array<int, string>
     */
    public array $airlineCodes;

    public int $sleepMsBetweenRequests;

    public ?int $maxAirlines;

    /**
     * @param array<int, string> $airlineCodes
     */
    public function __construct(array $airlineCodes = [], int $sleepMsBetweenRequests = 220, ?int $maxAirlines = null)
    {
        $this->airlineCodes = $airlineCodes;
        $this->sleepMsBetweenRequests = $sleepMsBetweenRequests;
        $this->maxAirlines = $maxAirlines;
    }

    public function handle(): void
    {
        $service = app(AmadeusService::class);
        $client = $service->getClient();
        $baseUrl = $service->getBaseUrl();
        $url = $baseUrl . "/v1/reference-data/airlines";

        $token = $service->getToken();
        if (!$token) {
            Log::error("BuildFlightSearchAirlinesCache: unable to get Amadeus token");
            return;
        }

        $codes = $this->airlineCodes;
        if (empty($codes)) {
            // IATA airline codes are typically 2 letters. This generates AA..ZZ (676 codes).
            $codes = [];
            for ($i = ord('A'); $i <= ord('Z'); $i++) {
                for ($j = ord('A'); $j <= ord('Z'); $j++) {
                    $codes[] = chr($i) . chr($j);
                }
            }
        }

        $airlines = [];
        $found = 0;

        foreach ($codes as $code) {
            $code = strtoupper(trim((string) $code));
            if ($code === '' || strlen($code) !== 2) {
                continue;
            }

            try {
                $resp = $client->get($url, [
                    "headers" => [
                        "Authorization" => "Bearer " . $token,
                        "Accept" => "application/json",
                    ],
                    "query" => [
                        "airlineCodes" => $code,
                    ],
                ]);

                $body = json_decode($resp->getBody(), true);
                $dataArr = $body["data"] ?? [];
                if (is_array($dataArr) && !empty($dataArr)) {
                    $first = $dataArr[0];
                    $airlineCode = strtoupper((string) ($first["iataCode"] ?? $first["airlineCode"] ?? $first["code"] ?? $code));
                    $airlineName = (string) ($first["businessName"] ?? $first["name"] ?? $first["label"] ?? $airlineCode);

                    $airlines[] = [
                        "code" => $airlineCode,
                        "name" => $airlineName,
                    ];

                    $found++;
                    if ($this->maxAirlines !== null && $found >= $this->maxAirlines) {
                        break;
                    }
                }

                // Small delay to reduce rate-limit risk.
                if ($this->sleepMsBetweenRequests > 0) {
                    usleep($this->sleepMsBetweenRequests * 1000);
                }
            } catch (\Throwable $e) {
                $msg = $e->getMessage();

                // If token expired / invalid, refresh and retry next iteration.
                if (str_contains(strtolower($msg), "401")) {
                    $token = $service->getToken();
                    usleep(300000);
                    continue;
                }

                // If rate limited, backoff and continue.
                if (str_contains($msg, "429")) {
                    usleep(1500000);
                    continue;
                }

                Log::warning("BuildFlightSearchAirlinesCache: failed for code={$code}: {$msg}");
            }
        }

        // Deduplicate by code.
        $byCode = [];
        foreach ($airlines as $a) {
            $byCode[strtoupper((string) ($a["code"] ?? ''))] = $a["name"] ?? '';
        }

        ksort($byCode);

        $finalAirlines = [];
        foreach ($byCode as $c => $name) {
            $finalAirlines[] = [
                "code" => $c,
                "name" => (string) $name,
            ];
        }

        $payload = [
            "generated_at" => now()->toISOString(),
            "airlines" => $finalAirlines,
        ];

        $path = storage_path("app/flightsearch/airlines.json");
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $tmpPath = $path . ".tmp";
        file_put_contents($tmpPath, json_encode($payload, JSON_UNESCAPED_UNICODE));
        rename($tmpPath, $path);

        Log::info("BuildFlightSearchAirlinesCache: wrote " . count($finalAirlines) . " airlines to {$path}");
    }
}


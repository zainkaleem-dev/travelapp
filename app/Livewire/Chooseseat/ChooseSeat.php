<?php

namespace App\Livewire\Chooseseat;

use Livewire\Component;

class ChooseSeat extends Component
{
    // ── Seat map config ──────────────────────────────────────────────────
    public int $totalRows = 30;
    public array $extraLegroomRows = [1, 2, 10, 11, 20];
    public array $occupiedSeats = [
        '3A',
        '3C',
        '4B',
        '5A',
        '5F',
        '6D',
        '7B',
        '7E',
        '8A',
        '8C',
        '9D',
        '9F',
        '12A',
        '12E',
        '13B',
        '13F',
        '14C',
        '14D',
        '15A',
        '15E',
        '16B',
        '17C',
        '18D',
        '18F',
        '19A',
        '19E',
        '21B',
        '21D',
        '22A',
        '22F',
        '23C',
        '23E',
        '24A',
        '24B',
        '25D',
        '25F',
        '26B',
        '26E',
        '27C',
        '28A',
        '28F',
    ];

    // ── State ────────────────────────────────────────────────────────────
    public ?string $selectedSeat = '24E';   // currently selected seat
    public int $activePassenger = 1;        // which passenger tab is active

    // ── Passengers ───────────────────────────────────────────────────────
    public array $passengers = [
        1 => ['label' => 'Passenger 1 · Outbound Date', 'seat' => '24E'],
        2 => ['label' => 'Passenger 2 · Outbound Date', 'seat' => null],
    ];

    // ── Pricing ──────────────────────────────────────────────────────────
    public float $outboundPrice = 179.00;
    public float $returnPrice = 1037.00;
    public float $extraBaggage = 17.00;
    public float $travelInsurance = 17.00;
    public float $seatTaxes = 31.00;

    // ── Computed helpers ─────────────────────────────────────────────────

    public function getSeatStateAttribute(int $row, string $col): string
    {
        $seatId = $row . $col;

        if ($seatId === $this->selectedSeat) {
            return 'selected';
        }
        if (in_array($seatId, $this->occupiedSeats, true)) {
            return 'occupied';
        }
        if (in_array($row, $this->extraLegroomRows, true)) {
            return 'extra';
        }
        return 'available';
    }

    public function isExtraLegroom(int $row): bool
    {
        return in_array($row, $this->extraLegroomRows, true);
    }

    public function getTotal(): float
    {
        return $this->outboundPrice
            + $this->returnPrice
            + $this->extraBaggage
            + $this->travelInsurance
            + $this->seatTaxes;
    }

    // ── Actions ──────────────────────────────────────────────────────────

    /** Called when the user clicks an available seat button */
    public function selectSeat(string $seatId): void
    {
        // Ignore occupied seats (guard)
        if (in_array($seatId, $this->occupiedSeats, true)) {
            return;
        }

        // Toggle off if clicking the already-selected seat
        if ($this->selectedSeat === $seatId) {
            $this->selectedSeat = null;
            $this->passengers[$this->activePassenger]['seat'] = null;
            return;
        }

        $this->selectedSeat = $seatId;
        $this->passengers[$this->activePassenger]['seat'] = $seatId;
    }

    /** Switch active passenger tab */
    public function switchPassenger(int $passengerIndex): void
    {
        $this->activePassenger = $passengerIndex;
        // Restore the seat selection for the newly active passenger
        $this->selectedSeat = $this->passengers[$passengerIndex]['seat'] ?? null;
    }

    public function removeExtraBaggage(): void
    {
        $this->extraBaggage = 0.00;
    }

    public function removeTravelInsurance(): void
    {
        $this->travelInsurance = 0.00;
    }

    public function removeSeatTaxes(): void
    {
        $this->seatTaxes = 0.00;
    }

    // ── Render ───────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.chooseseat.choose-seat', [
            'total' => $this->getTotal(),
            'columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
        ])->layout('layouts.flight');
    }

}

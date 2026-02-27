<?php

namespace App\Livewire\Additionalservices;

use Livewire\Component;

class AdditionalServices extends Component
{

    // ── Travel Insurance ─────────────────────────────────────────────────
    public string $insuranceOption = 'yes';   // 'yes' | 'no'
    public float $insurancePrice = 77.00;

    // ── Extra Baggage ────────────────────────────────────────────────────
    public bool $baggageEnabled = false;
    public int $baggageQty = 1;
    public float $baggagePrice = 59.00;   // price per unit

    // ── Summary fixed lines ───────────────────────────────────────────────
    public float $outboundPrice = 179.00;
    public float $returnPrice = 1037.00;
    public float $extraBaggageFee = 17.00;   // fee already in summary
    public float $seatTaxes = 17.00;

    // Whether each summary fee is removed
    public bool $extraBaggageFeeRemoved = false;
    public bool $travelInsuranceFeeRemoved = false;
    public bool $seatTaxesRemoved = false;

    // ── Computed total ────────────────────────────────────────────────────
    public function getTotal(): float
    {
        $total = $this->outboundPrice + $this->returnPrice;

        if (!$this->extraBaggageFeeRemoved) {
            $total += $this->extraBaggageFee;
        }
        if (!$this->travelInsuranceFeeRemoved) {
            $total += 17.00;  // fixed insurance fee line in summary
        }
        if (!$this->seatTaxesRemoved) {
            $total += $this->seatTaxes;
        }

        return $total;
    }

    // ── Actions ───────────────────────────────────────────────────────────

    public function setInsurance(string $value): void
    {
        $this->insuranceOption = $value;
    }

    public function toggleBaggage(): void
    {
        $this->baggageEnabled = !$this->baggageEnabled;
        if (!$this->baggageEnabled) {
            $this->baggageQty = 1;
        }
    }

    public function incrementBaggage(): void
    {
        $this->baggageQty++;
    }

    public function decrementBaggage(): void
    {
        if ($this->baggageQty > 0) {
            $this->baggageQty--;
        }
    }

    public function removeExtraBaggage(): void
    {
        $this->extraBaggageFeeRemoved = true;
    }

    public function removeTravelInsurance(): void
    {
        $this->travelInsuranceFeeRemoved = true;
        $this->insuranceOption = 'no';
    }

    public function removeSeatTaxes(): void
    {
        $this->seatTaxesRemoved = true;
    }

    // ── Render ────────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.additionalservices.additional-services', [
            'total' => $this->getTotal(),
        ])->layout('layouts.flight');
        ;
    }

}

<?php

namespace App\Livewire\Chooseseat;

use Livewire\Component;

class ChooseSeat extends Component
{
    // ─── Seat configuration ──────────────────────────────────────────────────

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

    /** Currently selected seat (null = none) */
    public ?string $selectedSeat = '24E';

    /** Total rows in the plane */
    public int $totalRows = 30;

    // ─── Computed helpers ─────────────────────────────────────────────────────

    /** Returns the seat state string for a given seatId */
    public function seatState(int $row, string $col): string
    {
        $seatId = $row . $col;

        if ($seatId === $this->selectedSeat) {
            return 'selected';
        }

        if (in_array($seatId, $this->occupiedSeats)) {
            return 'occupied';
        }

        if (in_array($row, $this->extraLegroomRows)) {
            return 'extra';
        }

        return 'available';
    }

    /** Toggle seat selection */
    public function selectSeat(string $seatId): void
    {
        // Deselect if already selected
        if ($this->selectedSeat === $seatId) {
            $this->selectedSeat = null;
            return;
        }

        // Ignore occupied seats
        if (in_array($seatId, $this->occupiedSeats)) {
            return;
        }

        $this->selectedSeat = $seatId;
    }

    public function continue(): void
    {
        if ($this->selectedSeat) {
            session(['booking_seat' => $this->selectedSeat]);
        }

        $this->redirect(route('passenger.details'), navigate: true);
    }

    public function back(): void
    {
        $this->redirect(route('additional.services'), navigate: true);
    }

    public function render()
    {
        $rows = [];

        for ($row = 1; $row <= $this->totalRows; $row++) {
            $seats = [];
            foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $col) {
                $seatId = $row . $col;
                $seats[$col] = [
                    'id' => $seatId,
                    'state' => $this->seatState($row, $col),
                    'isExtra' => in_array($row, $this->extraLegroomRows),
                ];
            }

            $rows[] = [
                'number' => $row,
                'isExtra' => in_array($row, $this->extraLegroomRows),
                'seats' => $seats,
            ];
        }

        return view('livewire.choose-seat', [
            'rows' => $rows,
        ]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Pages\Flights\ListingOneway;

class ListingOnewayTest extends TestCase
{
    /** @test */
    public function component_renders()
    {
        Livewire::test(ListingOneway::class)
            ->assertStatus(200);
    }

    /** @test */
    public function can_search_and_receive_dummy_results()
    {
        $departure = now()->addDay()->toDateString();

        Livewire::test(ListingOneway::class)
            ->set('oneWayOrigin', 'LON')
            ->set('oneWayDestination', 'NYC')
            ->set('oneWayDepartureDate', $departure)
            ->call('searchFlights')
            ->assertNotEmpty('flightResults.data');
    }
}

<?php

namespace Tests\Unit\Enums;

use App\Enums\ParkingType;
use PHPUnit\Framework\TestCase;

class ParkingTypeTest extends TestCase
{
    public function test_it_has_all_enum_cases(): void
    {
        $cases = ParkingType::cases();

        $this->assertCount(7, $cases);
        $this->assertContains(ParkingType::GARAGE, $cases);
        $this->assertContains(ParkingType::CARPORT, $cases);
        $this->assertContains(ParkingType::DRIVEWAY, $cases);
        $this->assertContains(ParkingType::PARKING_SPOT, $cases);
        $this->assertContains(ParkingType::UNDERGROUND, $cases);
        $this->assertContains(ParkingType::COVERED, $cases);
        $this->assertContains(ParkingType::STREET, $cases);
    }

    public function test_label_returns_correct_value_for_garage(): void
    {
        $this->assertEquals('Garage', ParkingType::GARAGE->label());
    }

    public function test_label_returns_correct_value_for_carport(): void
    {
        $this->assertEquals('Carport', ParkingType::CARPORT->label());
    }

    public function test_label_returns_correct_value_for_driveway(): void
    {
        $this->assertEquals('Oprit', ParkingType::DRIVEWAY->label());
    }

    public function test_label_returns_correct_value_for_parking_spot(): void
    {
        $this->assertEquals('Parkeerplaats', ParkingType::PARKING_SPOT->label());
    }

    public function test_label_returns_correct_value_for_underground(): void
    {
        $this->assertEquals('Ondergrondse parking', ParkingType::UNDERGROUND->label());
    }

    public function test_label_returns_correct_value_for_covered(): void
    {
        $this->assertEquals('Overdekte parking', ParkingType::COVERED->label());
    }

    public function test_label_returns_correct_value_for_street(): void
    {
        $this->assertEquals('Straatparkeren', ParkingType::STREET->label());
    }
}

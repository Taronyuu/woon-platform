<?php

namespace Tests\Unit\Enums;

use App\Enums\OutdoorSpaceType;
use PHPUnit\Framework\TestCase;

class OutdoorSpaceTypeTest extends TestCase
{
    public function test_it_has_all_enum_cases(): void
    {
        $cases = OutdoorSpaceType::cases();

        $this->assertCount(9, $cases);
        $this->assertContains(OutdoorSpaceType::GARDEN, $cases);
        $this->assertContains(OutdoorSpaceType::BALCONY, $cases);
        $this->assertContains(OutdoorSpaceType::ROOF_TERRACE, $cases);
        $this->assertContains(OutdoorSpaceType::TERRACE, $cases);
        $this->assertContains(OutdoorSpaceType::PATIO, $cases);
        $this->assertContains(OutdoorSpaceType::COURTYARD, $cases);
        $this->assertContains(OutdoorSpaceType::VERANDA, $cases);
        $this->assertContains(OutdoorSpaceType::FRONT_GARDEN, $cases);
        $this->assertContains(OutdoorSpaceType::BACK_GARDEN, $cases);
    }

    public function test_label_returns_correct_value_for_garden(): void
    {
        $this->assertEquals('Tuin', OutdoorSpaceType::GARDEN->label());
    }

    public function test_label_returns_correct_value_for_balcony(): void
    {
        $this->assertEquals('Balkon', OutdoorSpaceType::BALCONY->label());
    }

    public function test_label_returns_correct_value_for_roof_terrace(): void
    {
        $this->assertEquals('Dakterras', OutdoorSpaceType::ROOF_TERRACE->label());
    }

    public function test_label_returns_correct_value_for_terrace(): void
    {
        $this->assertEquals('Terras', OutdoorSpaceType::TERRACE->label());
    }

    public function test_label_returns_correct_value_for_patio(): void
    {
        $this->assertEquals('Patio', OutdoorSpaceType::PATIO->label());
    }

    public function test_label_returns_correct_value_for_courtyard(): void
    {
        $this->assertEquals('Binnenplaats', OutdoorSpaceType::COURTYARD->label());
    }

    public function test_label_returns_correct_value_for_veranda(): void
    {
        $this->assertEquals('Veranda', OutdoorSpaceType::VERANDA->label());
    }

    public function test_label_returns_correct_value_for_front_garden(): void
    {
        $this->assertEquals('Voortuin', OutdoorSpaceType::FRONT_GARDEN->label());
    }

    public function test_label_returns_correct_value_for_back_garden(): void
    {
        $this->assertEquals('Achtertuin', OutdoorSpaceType::BACK_GARDEN->label());
    }
}

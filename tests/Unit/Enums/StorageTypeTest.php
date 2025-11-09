<?php

namespace Tests\Unit\Enums;

use App\Enums\StorageType;
use PHPUnit\Framework\TestCase;

class StorageTypeTest extends TestCase
{
    public function test_it_has_all_enum_cases(): void
    {
        $cases = StorageType::cases();

        $this->assertCount(5, $cases);
        $this->assertContains(StorageType::BASEMENT, $cases);
        $this->assertContains(StorageType::ATTIC, $cases);
        $this->assertContains(StorageType::SHED, $cases);
        $this->assertContains(StorageType::BOX_ROOM, $cases);
        $this->assertContains(StorageType::EXTERNAL, $cases);
    }

    public function test_label_returns_correct_value_for_basement(): void
    {
        $this->assertEquals('Kelder', StorageType::BASEMENT->label());
    }

    public function test_label_returns_correct_value_for_attic(): void
    {
        $this->assertEquals('Zolder', StorageType::ATTIC->label());
    }

    public function test_label_returns_correct_value_for_shed(): void
    {
        $this->assertEquals('Schuur', StorageType::SHED->label());
    }

    public function test_label_returns_correct_value_for_box_room(): void
    {
        $this->assertEquals('Berging', StorageType::BOX_ROOM->label());
    }

    public function test_label_returns_correct_value_for_external(): void
    {
        $this->assertEquals('Externe berging', StorageType::EXTERNAL->label());
    }
}

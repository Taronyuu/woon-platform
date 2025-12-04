<?php

namespace Tests\Feature\Security;

use App\Models\PropertyUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InputValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_xss_in_search_query_is_escaped(): void
    {
        PropertyUnit::factory()->available()->create();

        $xssPayload = '<script>alert("xss")</script>';

        $response = $this->get('/zoeken?search=' . urlencode($xssPayload));

        $response->assertStatus(200);
        $response->assertDontSee('<script>alert', false);
    }

    public function test_sql_injection_in_search_is_prevented(): void
    {
        PropertyUnit::factory()->available()->create(['title' => 'Normal Property']);

        $sqlPayload = "'; DROP TABLE property_units; --";

        $response = $this->get('/zoeken?search=' . urlencode($sqlPayload));

        $response->assertStatus(200);
    }

    public function test_malformed_price_filter_handled_gracefully(): void
    {
        PropertyUnit::factory()->available()->create();

        $response = $this->get('/zoeken?min_price=abc&max_price=xyz');

        $response->assertStatus(200);
    }
}

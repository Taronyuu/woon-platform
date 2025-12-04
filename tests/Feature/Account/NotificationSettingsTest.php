<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_enable_new_properties_notifications(): void
    {
        $user = User::factory()->withNotificationsDisabled()->create();

        $response = $this->actingAs($user)->post('/account/notifications', [
            'notify_new_properties' => true,
        ]);

        $response->assertRedirect();
        $this->assertTrue($user->fresh()->notify_new_properties);
    }

    public function test_user_can_disable_new_properties_notifications(): void
    {
        $user = User::factory()->withNotificationsEnabled()->create();

        $response = $this->actingAs($user)->post('/account/notifications', []);

        $response->assertRedirect();
        $this->assertFalse($user->fresh()->notify_new_properties);
    }

    public function test_user_can_toggle_price_change_notifications(): void
    {
        $user = User::factory()->withNotificationsDisabled()->create();

        $response = $this->actingAs($user)->post('/account/notifications', [
            'notify_price_changes' => true,
        ]);

        $response->assertRedirect();
        $this->assertTrue($user->fresh()->notify_price_changes);
    }

    public function test_user_can_toggle_newsletter(): void
    {
        $user = User::factory()->create(['notify_newsletter' => false]);

        $response = $this->actingAs($user)->post('/account/notifications', [
            'notify_newsletter' => true,
        ]);

        $response->assertRedirect();
        $this->assertTrue($user->fresh()->notify_newsletter);
    }

    public function test_user_can_toggle_marketing_emails(): void
    {
        $user = User::factory()->create(['notify_marketing' => false]);

        $response = $this->actingAs($user)->post('/account/notifications', [
            'notify_marketing' => true,
        ]);

        $response->assertRedirect();
        $this->assertTrue($user->fresh()->notify_marketing);
    }

    public function test_notification_settings_save_shows_success_message(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/account/notifications', [
            'notify_new_properties' => true,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('notification_success', 'Je notificatie-instellingen zijn succesvol bijgewerkt.');
    }

    public function test_default_notification_values_for_new_user(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($user->notify_new_properties);
        $this->assertTrue($user->notify_price_changes);
        $this->assertFalse($user->notify_newsletter);
        $this->assertFalse($user->notify_marketing);
    }
}

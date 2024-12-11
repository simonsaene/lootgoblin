<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\GrindSpot;
use App\Models\GrindSession;
use Illuminate\Support\Str;


class GrindSessionTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_found_for_grind_sessions(): void
    {
        $user = User::factory()->create([
            'name' => 'John' . Str::random(5), // Add name here
            'family_name' => 'Doe', // Add family_name here
        ]);

        $grindSpot = GrindSpot::factory()->create(); // Assuming you have a GrindSpot factory
        $grindSession = GrindSession::factory()->create([
            'user_id' => $user->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 1,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('grind.player', ['id' => $user->id]));

        $response->assertStatus(200);
    }
    public function test_user_not_found_for_grind_sessions(): void
    {
        $user = User::factory()->create([
            'name' => 'John' . Str::random(5),
            'family_name' => 'Doe',
        ]);

        $this->actingAs($user);
        $response = $this->get(route('grind.player', ['id' => 99999]));

        $response->assertRedirect(route('user.home'));
        $response->assertSessionHas('error', 'User not found.');
    }

    public function test_add_grind_session_successful(): void
    {
        $user = User::factory()->create();
        $grindSpot = GrindSpot::factory()->create();
        $data = [
            'grind_spot_id' => $grindSpot->id,
            'hours' => 1,
            'loot_image' => null,
            'video_link' => null,
            'notes' => 'Test session',
            'is_video_verified' => false,
            'is_image_verified' => false,
        ];

        $this->actingAs($user);
        $response = $this->post(route('grind.session.add'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('grind_sessions', [
            'user_id' => $user->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 1,
            'notes' => 'Test session',
            'is_video_verified' => false,
            'is_image_verified' => false,
        ]);
    }

    public function test_add_grind_session_failed(): void
    {
        $user = User::factory()->create();
        $grindSpot = GrindSpot::factory()->create();
        
        $data = [
            'grind_spot_id' => $grindSpot->id,
            'hours' => 0,
            'loot_image' => null, 
            'video_link' => null,
            'notes' => 'Test session',
            'is_video_verified' => false,
            'is_image_verified' => false,
        ];

        $this->actingAs($user);

        $response = $this->post(route('grind.session.add'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('grind_sessions', [
            'user_id' => $user->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 1,
            'notes' => 'Test session',
            'is_video_verified' => false,
            'is_image_verified' => false,
        ]);
    }



    public function test_edit_grind_session_successful(): void
    {
        $user = User::factory()->create();
        $grindSpot = GrindSpot::factory()->create();

        $grindSession = GrindSession::factory()->create([
            'user_id' => $user->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 1,
            'loot_image' => null,
            'video_link' => null,
            'notes' => 'Old test session',
            'is_video_verified' => false,
            'is_image_verified' => false,
        ]);

        $data = [
            'user_id' => $user->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 2,
            'loot_image' => null,
            'video_link' => 'https://new-video-link.com',
            'notes' => 'Updated test session',
            'is_video_verified' => true,
            'is_image_verified' => true,
        ];

        $this->actingAs($user);

        $response = $this->put(route('grind.session.edit', ['id' => $grindSession->id]), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('grind_sessions', [
            'user_id' => $user->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 2,
            'loot_image' => null,
            'video_link' => 'https://new-video-link.com',
            'notes' => 'Updated test session',
            'is_video_verified' => true,
            'is_image_verified' => true,
        ]);
    }

    public function test_edit_grind_session_failed(): void
    {
        $user = User::factory()->create();
        $grindSpot = GrindSpot::factory()->create();

        $grindSession = GrindSession::factory()->create([
            'user_id' => $user->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 1,
            'loot_image' => null,
            'video_link' => null,
            'notes' => 'Old test session',
            'is_video_verified' => false,
            'is_image_verified' => false,
        ]);

        $anotherUser = User::factory()->create();

        $data = [
            'user_id' => $anotherUser->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 0,
            'loot_image' => null,
            'video_link' => 'https://new-video-link.com',
            'notes' => 'Updated test session',
            'is_video_verified' => true,
            'is_image_verified' => true,
        ];

        $this->actingAs($anotherUser);

        $response = $this->put(route('grind.session.edit', ['id' => $grindSession->id]), $data);

        $response->assertStatus(302);
        
        $this->assertDatabaseMissing('grind_sessions', [
            'user_id' => $anotherUser->id,
            'grind_spot_id' => $grindSpot->id,
            'hours' => 0,
            'loot_image' => null,
            'video_link' => 'https://new-video-link.com',
            'notes' => 'Updated test session',
            'is_video_verified' => true,
            'is_image_verified' => true,
        ]);
    }
    public function test_delete_grind_session_successful(): void
    {
        $user = User::factory()->create();
        $grindSpot = GrindSpot::factory()->create();

        $grindSession = GrindSession::factory()->create([
            'user_id' => $user->id,
            'grind_spot_id' => $grindSpot->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('grind.session.delete', ['id' => $grindSession->id]));

        $response->assertRedirect(route('grind.location', ['id' => $grindSpot->id]));

        $this->assertDatabaseMissing('grind_sessions', [
            'id' => $grindSession->id,
        ]);
    }
    public function test_delete_grind_session_failed(): void
    {
        $user = User::factory()->create();
        $grindSpot = GrindSpot::factory()->create();

        $anotherUser = User::factory()->create();

        $grindSession = GrindSession::factory()->create([
            'user_id' => $anotherUser->id,
            'grind_spot_id' => $grindSpot->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('grind.session.delete', ['id' => $grindSession->id]));
        $response->assertStatus(302);
        $this->assertDatabaseHas('grind_sessions', [
            'id' => $grindSession->id,
        ]);
    }
}

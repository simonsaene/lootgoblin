<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HomeProfileTest extends TestCase
{
    use RefreshDatabase;
    public function test_home_profile_contains_characters(): void
    {
        $user = User::factory()->create([
            'name' => 'John' . Str::random(5),
            'family_name' => 'Doe',
        ]);

        $this->actingAs($user);

        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('No character');
    }

    public function test_home_profile_contains_no_characters(): void
    {
        $user = User::factory()->create([
            'name' => 'John' . Str::random(5),
            'family_name' => 'Doe',
        ]);

        $this->actingAs($user);

        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('No character');
    }

    public function test_home_profile_upload_profile_image_successful(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('profile_image.jpg');

        $response = $this->put(route('user.edit.profile.image'), [
            'profile_image' => $file,
        ]);

        $response->assertStatus(302); 

        Storage::disk('public')->assertExists('home_profile_images/' . $file->hashName());

        $user->refresh();
        $this->assertEquals('home_profile_images/' . $file->hashName(), $user->profile_image);
    }


    public function test_home_profile_upload_profile_image_failed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Storage::fake('public');

        $file = UploadedFile::fake()->create('profile_image.txt', 100);

        $response = $this->put(route('user.edit.profile.image'), [
            'profile_image' => $file,
        ]);

        $response->assertStatus(302); 

        Storage::disk('public')->assertMissing('home_profile_images/' . $file->hashName());

        $user->refresh();
        $this->assertNull($user->profile_image);
    }

}
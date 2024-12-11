<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Character;
use App\Models\Favourite;
use App\Models\GrindSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CharacterTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_character_with_image_successful(): void
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('profile_image.jpg');
        $data = [
            'name' => 'John Doe',
            'class' => 'Warrior',
            'level' => 10,
            'profile_image' => $file,
        ];

        $response = $this->post(route('characters.create'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('characters', [
            'name' => 'John Doe',
            'class' => 'Warrior',
            'level' => 10,
        ]);

        Storage::disk('public')->assertExists('profile_images/' . $file->hashName());

        $response->assertSessionHas('status', 'Character added successfully!');
    }

    public function test_add_character_without_image_successful(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'name' => 'Jane Doe',
            'class' => 'Sorceress',
            'level' => 15,
        ];

        $response = $this->post(route('characters.create'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('characters', [
            'name' => 'Jane Doe',
            'class' => 'Sorceress',
            'level' => 15,
            'profile_image' => null,
        ]);
    }

    public function test_create_and_edit_character_level_successful(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('public'); 
        $file = UploadedFile::fake()->image('profile_image.jpg');

        $characterData = [
            'name' => 'New Character',
            'class' => 'Warrior',
            'level' => 10,
            'profile_image' => $file,
        ];

        $response = $this->post(route('characters.create'), $characterData);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('characters', [
            'name' => 'New Character',
            'class' => 'Warrior',
            'level' => 10,
        ]);
        
        Storage::disk('public')->assertExists('profile_images/' . $file->hashName());

        $updatedData = [
            'name' => 'Updated Character',
            'class' => 'Mage',
            'level' => 20,
        ];

        $character = Character::where('name', 'New Character')->first(); 

        $response = $this->put(route('characters.edit', $character->id), $updatedData);

        $response->assertStatus(302);

        $this->assertDatabaseHas('characters', [
            'id' => $character->id,
            'name' => 'Updated Character',
            'class' => 'Mage',
            'level' => 20,
        ]);
    }

    public function test_create_and_delete_character_successful(): void
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $characterData = [
            'name' => 'Character to Delete',
            'class' => 'Warrior',
            'level' => 10
        ];

        $response = $this->post(route('characters.create'), $characterData);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('characters', [
            'name' => 'Character to Delete',
            'class' => 'Warrior',
            'level' => 10,
        ]);

        $character = Character::where('name', 'Character to Delete')->first();

        $response = $this->delete(route('characters.delete', $character->id));

        $response->assertStatus(302);

        $this->assertDatabaseMissing('characters', [
            'id' => $character->id,
            'name' => 'Character to Delete',
        ]);
    }

    public function test_add_favourite_to_class_successful(): void
    {
        $user = User::factory()->create();
        $character = Character::factory()->create();
        $grindSpot = GrindSpot::factory()->create();

        $favourite = Favourite::factory()->create([
            'user_id' => $user->id,
            'character_id' => $character->id,
            'grind_spot_id' => $grindSpot->id,
        ]);

        $this->assertDatabaseHas('favourites', [
            'user_id' => $user->id,
            'character_id' => $character->id,
            'grind_spot_id' => $grindSpot->id,
        ]);
    }

    public function test_remove_favourite_from_class_successful(): void
    {
        $user = User::factory()->create();
        $character = Character::factory()->create();
        $grindSpot = GrindSpot::factory()->create();

        $favourite = Favourite::factory()->create([
            'user_id' => $user->id,
            'character_id' => $character->id,
            'grind_spot_id' => $grindSpot->id,
        ]);

        $this->assertDatabaseHas('favourites', [
            'user_id' => $user->id,
            'character_id' => $character->id,
            'grind_spot_id' => $grindSpot->id,
        ]);

        $favourite->delete();

        $this->assertDatabaseMissing('favourites', [
            'user_id' => $user->id,
            'character_id' => $character->id,
            'grind_spot_id' => $grindSpot->id,
        ]);
    }
}

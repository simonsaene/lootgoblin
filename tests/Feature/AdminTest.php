<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \App\Models\Item;
use \App\Models\User;
use \App\Models\GrindSpotItem;
use \App\Models\GrindSpot;
use Tests\TestCase;


class AdminTest extends TestCase
{
    public function test_create_item_as_admin_successful(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => 'Test Item',
            'market_value' => 1000,
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'market_value' => 1000,
        ]);
    }

    public function test_create_item_as_admin_failed(): void
    {
        $user = User::factory()->admin()->create();
    
        $this->actingAs($user);

        $response = $this->post(route('admin.items.add'), [
            'market_value' => 1000,
            'description' => 'Test item description',
        ]);

        $this->assertDatabaseMissing('items', [
            'market_value' => 1000,
            'description' => 'Test item description',
        ]);
    }

    public function test_edit_item_as_admin_successful(): void
    {
        // Create an admin user
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        // Create an initial item
        $item = Item::factory()->create([
            'name' => 'Test Item',
            'market_value' => 1000,
            'description' => 'Old description',
        ]);

        $currentVendorValue = $item->vendor_value;
        $currentImage = $item->image;

        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'market_value' => 1000,
            'description' => 'Old description',
        ]);


        $updatedData = [
            'name' => 'Updated Item',
            'market_value' => 1500,
            'description' => 'Updated description for the item.',
            'vendor_value' => $currentVendorValue,

        ];

        $response = $this->put(route('admin.items.edit', $item->id), $updatedData);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Updated Item',
            'market_value' => 1500,
            'description' => 'Updated description for the item.',
            'vendor_value' => $currentVendorValue, 
            'image' => $currentImage, 
        ]);

        $this->assertDatabaseMissing('items', [
            'name' => 'Test Item',
            'market_value' => 1000,
            'description' => 'Old description',
        ]);
    }

    public function test_edit_item_as_admin_failed(): void
    {

        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => 'Test Item',
            'market_value' => 1000,
            'description' => 'Old description',
        ]);

        $updatedData = [
            'market_value' => 1500,
            'description' => 'Updated description for the item.',
        ];

        $response = $this->put(route('admin.items.edit', $item->id), $updatedData);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Test Item',
            'market_value' => 1000,
            'description' => 'Old description',
        ]);
    }

    public function test_delete_item_as_admin_successful(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => 'Test Item',
            'market_value' => 1000,
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'market_value' => 1000,
        ]);

        $item->delete();

        $this->assertDatabaseMissing('items', [
            'id' => $item->id
        ]);
    }

    public function test_delete_non_existent_item_failed(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $response = $this->delete(route('admin.items.delete', 999));

        $response->assertStatus(302);

        $this->assertDatabaseMissing('items', [
            'id' => 999,
        ]);
    }
}

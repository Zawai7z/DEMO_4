<?php

namespace Tests\Feature;

use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlipPlayApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_crud_search_and_validation(): void
    {
        $this->seed(DatabaseSeeder::class);

        // 1. Index + search filter
        $this->getJson('/api/media')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');

        $this->getJson('/api/media?search=Solo')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Solo Leveling: Arise');

        // 2. Validation 422 on empty/invalid POST
        $this->postJson('/api/media', [
            'title' => '',
            'type' => 'invalid',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'type', 'genres', 'score', 'synopsis']);

        // 3. Duplicate title rejected with 422
        $this->postJson('/api/media', [
            'title' => 'Solo Leveling: Arise',
            'type' => 'series',
            'genres' => ['Action'],
            'score' => 8.5,
            'synopsis' => 'Duplicate title test.',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['title']);

        // 4. Create new media -> 201 Created
        $createResponse = $this->postJson('/api/media', [
            'title' => 'Attack on Titan',
            'type' => 'series',
            'genres' => ['Action', 'Drama'],
            'score' => 9.1,
            'synopsis' => 'Pertarungan umat manusia melawan Titan.',
            'episodes' => 25,
            'status' => 'Completed',
            'season' => 'Spring 2013',
        ]);
        $createResponse->assertStatus(201)
            ->assertJsonPath('data.title', 'Attack on Titan');

        $mediaId = $createResponse->json('data.id');

        // 5. Show detail with users relation -> 200 OK
        $this->getJson("/api/media/{$mediaId}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $mediaId)
            ->assertJsonStructure(['data' => ['id', 'title', 'type', 'genres', 'users']]);

        // 6. Partial PATCH update -> 200 OK
        $this->patchJson("/api/media/{$mediaId}", [
            'score' => 9.25,
        ])->assertStatus(200)
            ->assertJsonPath('data.score', 9.25);

        // 7. Delete media -> 200 OK, then 404 on show
        $this->deleteJson("/api/media/{$mediaId}")
            ->assertStatus(200);

        $this->getJson("/api/media/{$mediaId}")
            ->assertStatus(404);
    }

    public function test_user_collection_attach_detach_and_conflict_handling(): void
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::where('email', 'zaidan@student.umm.ac.id')->firstOrFail();
        $media = Media::where('title', 'Solo Leveling: Arise')->firstOrFail();

        // 1. Add media to user collection -> 200 OK
        $this->postJson("/api/users/{$user->id}/collection", [
            'media_id' => $media->id,
        ])->assertStatus(200)
            ->assertJsonPath('message', 'Media berhasil ditambahkan ke koleksi')
            ->assertJsonPath('data.media.0.id', $media->id);

        // 2. Adding same media again -> 409 Conflict
        $this->postJson("/api/users/{$user->id}/collection", [
            'media_id' => $media->id,
        ])->assertStatus(409)
            ->assertJsonPath('message', 'Media sudah ada di koleksi pengguna');

        // 3. Adding nonexistent media_id -> 422
        $this->postJson("/api/users/{$user->id}/collection", [
            'media_id' => 9999,
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['media_id']);

        // 4. Get user detail & collection endpoint
        $this->getJson("/api/users/{$user->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.media.0.title', 'Solo Leveling: Arise');

        $this->getJson("/api/users/{$user->id}/collection")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');

        // 5. Show media includes user who collected it
        $this->getJson("/api/media/{$media->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.users.0.id', $user->id);

        // 6. Detach media from collection -> 200 OK
        $this->deleteJson("/api/users/{$user->id}/collection/{$media->id}")
            ->assertStatus(200)
            ->assertJsonPath('message', 'Media berhasil dihapus dari koleksi');

        // 7. Detach again when not in collection -> 404
        $this->deleteJson("/api/users/{$user->id}/collection/{$media->id}")
            ->assertStatus(404);
    }

    public function test_resources_omit_relations_when_not_loaded(): void
    {
        $this->seed(DatabaseSeeder::class);

        $media = Media::firstOrFail();
        $user = User::firstOrFail();

        $mediaEncoded = json_decode(json_encode((new MediaResource($media))->resolve()), true);
        $userEncoded = json_decode(json_encode((new UserResource($user))->resolve()), true);

        $this->assertArrayNotHasKey('users', $mediaEncoded);
        $this->assertArrayNotHasKey('media', $userEncoded);
    }
}


<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionMediaRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Menampilkan semua pengguna beserta koleksi media masing-masing.
     */
    public function index()
    {
        $users = User::with('media')->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Menampilkan detail satu pengguna beserta koleksi medianya.
     */
    public function show(User $user)
    {
        return new UserResource($user->load('media'));
    }

    /**
     * Menambahkan pengguna baru.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Memperbarui data pengguna.
     */
    public function update(StoreUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Pengguna berhasil dihapus',
        ]);
    }

    /**
     * Menampilkan daftar media yang ada di koleksi pengguna.
     */
    public function collection(User $user)
    {
        return MediaResource::collection($user->media()->get());
    }

    /**
     * Menambahkan media ke koleksi pengguna (mencegah duplikat dengan status 409).
     */
    public function addToCollection(CollectionMediaRequest $request, User $user)
    {
        $mediaId = $request->validated()['media_id'];

        if ($user->media()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'message' => 'Media sudah ada di koleksi pengguna',
            ], 409);
        }

        $user->media()->attach($mediaId);

        return response()->json([
            'message' => 'Media berhasil ditambahkan ke koleksi',
            'data' => new UserResource($user->load('media')),
        ]);
    }

    /**
     * Menghapus media dari koleksi pengguna.
     */
    public function removeFromCollection(User $user, Media $media)
    {
        if (! $user->media()->where('media_id', $media->id)->exists()) {
            return response()->json([
                'message' => 'Media tidak ditemukan di koleksi pengguna',
            ], 404);
        }

        $user->media()->detach($media->id);

        return response()->json([
            'message' => 'Media berhasil dihapus dari koleksi',
            'data' => new UserResource($user->load('media')),
        ]);
    }
}


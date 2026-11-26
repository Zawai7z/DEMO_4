<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Menampilkan daftar media (mendukung filter ?search= dan ?type=).
     */
    public function index(Request $request)
    {
        $media = Media::with('users')
            ->search($request->query('search'))
            ->when($request->query('type'), fn ($query, $type) => $query->where('type', $type))
            ->paginate(10);

        return MediaResource::collection($media);
    }

    /**
     * Menampilkan detail satu media beserta pengguna yang mengoleksinya.
     */
    public function show(Media $media)
    {
        return new MediaResource($media->load('users'));
    }

    /**
     * Menambahkan media baru ke katalog FlipPlay.
     */
    public function store(StoreMediaRequest $request)
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Memperbarui data media yang sudah ada.
     */
    public function update(StoreMediaRequest $request, Media $media)
    {
        $media->update($request->validated());

        return new MediaResource($media);
    }

    /**
     * Menghapus media dari katalog.
     */
    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json([
            'message' => 'Media berhasil dihapus',
        ]);
    }
}


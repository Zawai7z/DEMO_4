<?php

namespace App\Http\Requests;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPatch = $this->isMethod('PATCH') || $this->isMethod('PUT');
        $mediaParam = $this->route('media');
        $mediaId = $mediaParam instanceof Media ? $mediaParam->id : $mediaParam;

        return [
            'title' => [
                $isPatch ? 'sometimes' : 'required',
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($mediaId),
            ],
            'type' => [$isPatch ? 'sometimes' : 'required', 'string', Rule::in(['series', 'movie'])],
            'genres' => [$isPatch ? 'sometimes' : 'required', 'array', 'min:1'],
            'genres.*' => ['string', 'max:50'],
            'score' => [$isPatch ? 'sometimes' : 'required', 'numeric', 'min:0', 'max:10'],
            'synopsis' => [$isPatch ? 'sometimes' : 'required', 'string'],
            'episodes' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'string', 'max:50'],
            'season' => ['nullable', 'string', 'max:50'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi',
            'title.unique' => 'Judul media sudah terdaftar',
            'type.required' => 'Jenis media wajib diisi',
            'type.in' => 'Jenis media harus bernilai series atau movie',
            'genres.required' => 'Genre media wajib diisi',
            'genres.array' => 'Genre harus berupa array',
            'score.required' => 'Skor media wajib diisi',
            'synopsis.required' => 'Sinopsis media wajib diisi',
        ];
    }
}


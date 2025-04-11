<?php

namespace App\Services;

use App\Models\Profil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService {

    /**
     * Store incoming file locally
     *
     * @param UploadedFile $file
     * @return Illuminate\Http\JsonResponse;
     */
    public function storeFile(UploadedFile $file): JsonResponse
    {
        if(file_exists($file)) {
            $folder = 'images';
            $disk = Storage::disk('public');
            $uniqueFilename = $this->uniqueMediaName($file, $folder, $disk);
            $path = $file->storeAs($folder, $uniqueFilename, 'public');
            $fileUrl = $disk->url($path);
            return response()->json([
                'file_url' => $fileUrl
            ], 201);
        } else {
            return response()->json(['Erreur' => 'L\'image est obligatoire'], 400);
        }
    }

	/**
	 * Generate unique file name with an uuid
     * @return string
	 */
	public function uniqueMediaName($file): string
	{
		$extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
	}

    /**
     * Find and replace local file for incoming profile
     *
     * @param UploadedFile $uploadedFile
     * @param Profil $profil
     * @return Illuminate\Http\JsonResponse;
     */
    public function findAndReplaceProfilFile(UploadedFile $uploadedFile, Profil $profil): JsonResponse {
        $oldFile = pathinfo($profil->image)['basename'];
        $newFileUrl = '';

        try {
            $newFileUrl = $this->storeFile($uploadedFile)->getData()->file_url;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'L\'image n\'a pas pu être remplacée.',
            ], 422);
        }

        Storage::delete($oldFile);

        return response()->json([
            'file_url' => $newFileUrl
        ], 201);
    }
}


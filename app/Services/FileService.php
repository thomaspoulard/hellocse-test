<?php

namespace App\Services;

use App\Models\Profil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileService {

    /**
     * Store incoming file locally
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse;
     */
    public function storeFile(Request $request): JsonResponse
    {
		if($request->hasFile('image')){
			$file = $request->file('image');
			if($file->isValid()){
				$folder = 'images';
				$disk = Storage::disk('public');
				$uniqueFilename = $this->uniqueMediaName($file, $folder, $disk);
				$path = $file->storeAs($folder, $uniqueFilename, 'public');
				$fileUrl = $disk->url($path);
                return response()->json([
                    'file_url' => $fileUrl
                ], 201);
            } else{
				return response()->json(['Erreur' => 'L\'image est obligatoire'], 400);
			}
		} else{
			return response()->json(['Erreur' => 'L\'image est obligatoire'], 400);
		}
	}// upload

	/**
	 * Generate unique file name
	 */
	public function uniqueMediaName($file, $folder, $disk)
	{
        // Replace spaces with underscores to avoid errors
		$filename = str_replace(' ', '_', $file->getClientOriginalName());
		$extension = $file->getClientOriginalExtension();

		$i = 1;
		$basename = basename($filename, '.' . $extension);
		$output = $folder.'/'.$filename;
		while($disk->exists($output)){
			$output = $folder .'/'. $basename .'-'. $i . '.' . $extension;
			$i++;
		}
		return basename($output);
	}

    /**
     * Find and replace local file for incoming profile
     *
     * @param Request $request
     * @param Profil $profil
     * @return Illuminate\Http\JsonResponse;
     */
    public function findAndReplaceProfilFile(Request $request, Profil $profil): JsonResponse {
        $oldFile = pathinfo($profil->image)['basename'];
        $newFileUrl = '';

        try {
            $newFileUrl = $this->storeFile($request)->getData()->file_url;
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


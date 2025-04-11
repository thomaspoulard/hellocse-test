<?php

namespace App\Services;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileService {

    /**
     * Store incoming image locally
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
				$imageUrl = $disk->url($path);
                return response()->json([
                    'image_url' => $imageUrl
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
     * Delete and replace the requested image upon profile modification
     *
     * @param Profil $profil
     * @return Illuminate\Http\RedirectResponse
     */
    /*
    public function updateProfileImage(Profil $profil): RedirectResponse {
        //TODO: Update profile image function
        return true;
    }
    */
}


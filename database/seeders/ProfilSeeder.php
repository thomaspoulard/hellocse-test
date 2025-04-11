<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Services\FileService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class ProfilSeeder extends Seeder
{
    // Dependency injection of TokenService for token management
    public function __construct(
        protected FileService $fileService,
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = Profil::factory()->count(10)->create();

        foreach($profiles as $profile) {
            $profile['image'] = $this->fileService->storeFile(new UploadedFile(public_path('images').'/placeholder.jpg', 'placeholder.jpg'))->getData()->file_url;
            $profile->save();
        }
    }
}

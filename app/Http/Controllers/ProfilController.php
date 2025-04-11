<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use App\Services\FileService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{

    // Dependency injection of TokenService for token management
    public function __construct(
        protected TokenService $tokenService,
        protected FileService $fileService
    ) {
        $this->tokenService = $tokenService;
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        $profiles = Profil::whereHas('statut', function ($q) {
            $q->where('nom', 'actif');
        })->select('id', 'nom', 'prenom');

        // Handle case when there are no active profiles
        if ($profiles->get()->isEmpty()) {
            return response(['message' => 'Il n\'y a aucun profil actif actuellement.'], 404);
        }

        // Handle case when admin is requesting the profiles
        if(auth()?->check()) {
            return response($profiles->select('id', 'nom', 'prenom', 'statut_id')->with(['statut:id,nom'])->get(), 200);
        }

        return response($profiles->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'image' => [
                    'required',
                    'image',
                    'mimes:jpg,png,jpeg,gif,svg',
                ],
                'statut_id' => [
                    'required',
                    'integer',
                    'exists:statuts,id'
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'La validation des champs a échoué.',
            ], 422);
        }

        $validated['image'] = $this->fileService->storeFile($request->file('image'))->getData()->file_url;

        $profil = Profil::create($validated);

        return response()->json($profil, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Illuminate\Http\Request
     * @param \App\Models\Profil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profil $profil): Response
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg',
            'statut_id' => 'sometimes|integer|exists:statuts,id',
        ]);

        // Handle cases when the updated value is the same as the initial value; early return
        if ($profil->isDirty()) {
            return response([
                'message' => 'Aucune modification détectée.',
            ], 422);
        }

        // Verify if the image changed
        if(isset($validated['image'])) {
            $validated['image'] = $this->fileService->findAndReplaceProfilFile($request->file('image'), $profil)->getData()->file_url;
            $profil->fill($validated);
        }


        $profil->update($validated);

        return response($profil, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Profil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profil $profil): Response
    {
        $profil->delete();

        return response('Le profil a été supprimé.', 200);
    }
}

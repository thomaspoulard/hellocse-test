<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profil::whereHas('statut', function ($query) {
            $query->where('nom', 'like', 'actif');
        })->select('nom', 'prenom', 'image', 'created_at', 'updated_at')
            ->get();

        if ($profiles->isEmpty()) {
            return response()->json(['message' => 'Il n\'y a aucun profil actif actuellement.'], 404);
        }

        //TODO: Handle auth status to see the "statut" field

        return response()->json($profiles, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
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
                    'max:2048'
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
                'message' => 'Validation failed',
            ], 422);
        }

        $profil = Profil::create($validated);

        //TODO: Handle local image storage or base64 here with an external and reusable method

        return response()->json($profil, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profil $profil)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg',
            'statut_id' => 'sometimes|integer|exists:statuts,id',
        ]);

        // Handle cases when the updated value is the same as the initial value; Skip API call and output unprocessable content error
        if (!$profil->isDirty()) {
            return response()->json([
                'message' => 'Aucune modification détectée.',
            ], 422);
        }

        $profil->update($validated);

        return response()->json($profil, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profil $profil)
    {
        $profil->delete();

        return response('Le profil a été supprimé.', 200);
    }
}

<?php

namespace App\Services;

use App\Models\Administrateur;
use Carbon\Carbon;
use Laravel\Sanctum\NewAccessToken;

class TokenService {

    /**
     * Generate a new token for the given admin
     *
     * @param Administrateur $admin
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createNewToken(Administrateur $admin): NewAccessToken {
        $accessToken = $admin->createToken('authToken', expiresAt: Carbon::now()->addMinutes(2));
        return $accessToken;
    }

    /**
     * Generate a new token for the given admin
     *
     * @param Administrateur $admin
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function findOrUpdateToken(Administrateur $admin): NewAccessToken {
        $mostRecentAccessToken = $admin?->tokens()->orderBy('expires_at', 'desc')->first();

        // Handle case when access token is expired and must be renewed
        if ($mostRecentAccessToken?->expires_at < now()) {
            return $this->createNewToken($admin);
        }

        // Standardize response to always be a NewAccessToken
        return new NewAccessToken($mostRecentAccessToken, $mostRecentAccessToken->token);
    }
}


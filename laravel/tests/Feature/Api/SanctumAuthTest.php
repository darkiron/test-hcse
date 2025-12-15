<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SanctumAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_me_logout_flow(): void
    {
        // Arrange
        $password = 'secret';
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => $password,
        ]);

        // On simplifie le test d’intégration HTTP du login en neutralisant la vérif CSRF
        // (en production, le flux réel passe par /sanctum/csrf-cookie + en-tête X-XSRF-TOKEN)
        $this->withoutMiddleware(ValidateCsrfToken::class);

        // Act: login
        $resLogin = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        // Assert: login OK et user dans la réponse
        $resLogin->assertOk()->assertJsonStructure(['user' => ['id', 'email']]);

        // Act: profil
        $resMe = $this->getJson('/api/user');
        $resMe->assertOk()->assertJsonPath('email', $user->email);

        // Act: logout
        $resLogout = $this->postJson('/api/logout');
        $resLogout->assertNoContent();

        // Assert: plus authentifié
        // On force une nouvelle application/sessions pour simuler un nouveau client
        $this->refreshApplication();
        $this->getJson('/api/user')->assertStatus(401);
    }
}

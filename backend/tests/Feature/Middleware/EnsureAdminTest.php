<?php

declare(strict_types=1);

namespace Tests\Unit\Middleware;

use App\Http\Middleware\EnsureAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EnsureAdminTest extends TestCase
{
    private function makeRequestWithUser($user): Request
    {
        $req = Request::create('/admin-test', 'GET');
        $req->setUserResolver(fn () => $user);
        return $req;
    }

    private function next()
    {
        return function ($req) {
            return response()->json(['passed' => true], 200);
        };
    }

    //────────────────────────────────────────────
    // 1) Admin → átengedi
    //────────────────────────────────────────────
    #[Test]
    public function test_allows_admin_user(): void
    {
        $admin = new User();
        $admin->role = 'admin';

        $middleware = new EnsureAdmin();
        $request = $this->makeRequestWithUser($admin);

        $response = $middleware->handle($request, $this->next());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            ['passed' => true],
            $response->getData(true)
        );
    }

    //────────────────────────────────────────────
    // 2) Nem admin → 403
    //────────────────────────────────────────────
    #[Test]
    public function test_blocks_non_admin_user(): void
    {
        $user = new User();
        $user->role = 'user';

        $middleware = new EnsureAdmin();
        $request = $this->makeRequestWithUser($user);

        $response = $middleware->handle($request, $this->next());

        $this->assertEquals(403, $response->getStatusCode());

        $json = $response->getData(true);
        $this->assertEquals('error', $json['status']);
        $this->assertEquals('Nincs jogosultságod ehhez a művelethez', $json['message']);
    }

    //────────────────────────────────────────────
    // 3) Nincs user → 403
    //────────────────────────────────────────────
    #[Test]
    public function test_blocks_when_no_user_logged_in(): void
    {
        $middleware = new EnsureAdmin();
        $request = $this->makeRequestWithUser(null);

        $response = $middleware->handle($request, $this->next());

        $this->assertEquals(403, $response->getStatusCode());

        $json = $response->getData(true);
        $this->assertEquals('error', $json['status']);
        $this->assertEquals('Nincs jogosultságod ehhez a művelethez', $json['message']);
    }
}

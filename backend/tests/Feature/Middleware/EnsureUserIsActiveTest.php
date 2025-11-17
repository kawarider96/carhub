<?php

declare(strict_types=1);

namespace Tests\Unit\Middleware;

use App\Http\Middleware\EnsureUserIsActive;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;

class EnsureUserIsActiveTest extends TestCase
{
    private function makeRequestWithUser($user): Request
    {
        $req = Request::create('/active-test', 'GET');
        $req->setUserResolver(fn () => $user);
        return $req;
    }

    private function next()
    {
        return function () {
            return response()->json(['passed' => true], 200);
        };
    }

    //────────────────────────────────────────────
    // 1) Aktív user → átengedi
    //────────────────────────────────────────────
    #[Test]
    public function test_allows_active_user(): void
    {
        $user = new User();
        $user->is_active = true;

        $middleware = new EnsureUserIsActive();
        $request = $this->makeRequestWithUser($user);

        $response = $middleware->handle($request, $this->next());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['passed' => true], $response->getData(true));
    }

    //────────────────────────────────────────────
    // 2) Inaktív user → blokkolás, 423
    //────────────────────────────────────────────
    #[Test]
    public function test_blocks_inactive_user(): void
    {
        $user = new User();
        $user->is_active = false;

        $middleware = new EnsureUserIsActive();
        $request = $this->makeRequestWithUser($user);

        $response = $middleware->handle($request, $this->next());

        $this->assertEquals(423, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertEquals('error', $data['status']);
        $this->assertEquals('A felhasználói fiók zárolva van', $data['message']);
    }

    //────────────────────────────────────────────
    // 3) Nincs user → TypeError
    //────────────────────────────────────────────
    #[Test]
    public function test_throws_error_when_user_is_null(): void
    {
        $middleware = new EnsureUserIsActive();

        $request = Request::create('/api/valami', 'GET');

        $response = $middleware->handle($request, fn() => response('OK'));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(401, $response->status());
        $this->assertEquals([
            'message' => 'Nincs bejelentkezve',
            'status' => false,
        ], $response->getData(true));
    }
}

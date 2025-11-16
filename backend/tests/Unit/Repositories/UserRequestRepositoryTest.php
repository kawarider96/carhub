<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Models\UserRequest;
use App\Repositories\UserRequestRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRequestRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRequestRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = new UserRequestRepository(new UserRequest());
    }

    // ─────────────────────────────────────────────
    // 1) openRequestsByUser – visszaadja a user OPEN requestjét
    // ─────────────────────────────────────────────
    #[Test]
    public function open_requests_by_user_returns_open_request(): void
    {
        $user = User::factory()->create();

        $open = UserRequest::factory()->create([
            'user_id' => $user->id,
            'status'  => 'open',
        ]);

        $res = $this->repo->openRequestsByUser($user->id);

        $this->assertNotNull($res);
        $this->assertEquals($open->id, $res->id);
    }

    // ─────────────────────────────────────────────
    // 2) openRequestsByUser – ha nincs találat → null
    // ─────────────────────────────────────────────
    #[Test]
    public function open_requests_by_user_returns_null_when_none(): void
    {
        $user = User::factory()->create();

        // nincs open státuszú request
        UserRequest::factory()->create([
            'user_id' => $user->id,
            'status'  => 'approved',
        ]);

        $res = $this->repo->openRequestsByUser($user->id);

        $this->assertNull($res);
    }

    // ─────────────────────────────────────────────
    // 3) openRequestsByUser – más user requestje NEM jelenik meg
    // ─────────────────────────────────────────────
    #[Test]
    public function open_requests_by_user_returns_only_given_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        UserRequest::factory()->create([
            'user_id' => $user2->id,
            'status' => 'open',
        ]);

        $user1Req = UserRequest::factory()->create([
            'user_id' => $user1->id,
            'status'  => 'open',
        ]);

        $res = $this->repo->openRequestsByUser($user1->id);

        $this->assertNotNull($res);
        $this->assertEquals($user1Req->id, $res->id);
    }

    // ─────────────────────────────────────────────
    // 4) findOpenByUserAndType – helyes találat
    // ─────────────────────────────────────────────
    #[Test]
    public function find_open_by_user_and_type_returns_correct_request(): void
    {
        $user = User::factory()->create();

        $req = UserRequest::factory()->create([
            'user_id' => $user->id,
            'type'    => 'delete_account',
            'status'  => 'open',
        ]);

        $res = $this->repo->findOpenByUserAndType($user->id, 'delete_account');

        $this->assertNotNull($res);
        $this->assertEquals($req->id, $res->id);
    }

    // ─────────────────────────────────────────────
    // 5) findOpenByUserAndType – rossz típus → null
    // ─────────────────────────────────────────────
    #[Test]
    public function find_open_by_user_and_type_returns_null_for_wrong_type(): void
    {
        $user = User::factory()->create();

        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type'    => 'missing_brand',
            'status'  => 'open',
        ]);

        $res = $this->repo->findOpenByUserAndType($user->id, 'delete_account');

        $this->assertNull($res);
    }

    // ─────────────────────────────────────────────
    // 6) findOpenByUserAndType – nem open → null
    // ─────────────────────────────────────────────
    #[Test]
    public function find_open_by_user_and_type_returns_null_when_not_open(): void
    {
        $user = User::factory()->create();

        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type'    => 'delete_account',
            'status'  => 'approved',
        ]);

        $res = $this->repo->findOpenByUserAndType($user->id, 'delete_account');

        $this->assertNull($res);
    }

    // ─────────────────────────────────────────────
    // 7) findOpenByUserAndType – több találat → első jön vissza
    // ─────────────────────────────────────────────
    #[Test]
    public function find_open_by_user_and_type_returns_first_when_multiple(): void
    {
        $user = User::factory()->create();

        $first = UserRequest::factory()->create([
            'user_id' => $user->id,
            'type'    => 'missing_brand',
            'status'  => 'open',
        ]);

        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type'    => 'missing_brand',
            'status'  => 'open',
        ]);

        $res = $this->repo->findOpenByUserAndType($user->id, 'missing_brand');

        $this->assertEquals($first->id, $res->id);
    }
}

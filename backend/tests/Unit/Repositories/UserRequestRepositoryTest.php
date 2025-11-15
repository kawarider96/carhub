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
    // 1) openRequests – visszaadja az összes "open" státuszú requestet
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_requests_returns_only_open_requests(): void
    {
        UserRequest::factory()->create(['status' => 'open']);
        UserRequest::factory()->create(['status' => 'approved']);
        UserRequest::factory()->create(['status' => 'rejected']);

        $res = $this->repo->openRequests();

        $this->assertCount(1, $res);
        $this->assertEquals('open', $res->first()->status);
    }

    // ─────────────────────────────────────────────
    // 2) openRequests – üres lista
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_requests_returns_empty_collection_when_none(): void
    {
        $res = $this->repo->openRequests();

        $this->assertCount(0, $res);
    }

    // ─────────────────────────────────────────────
    // 3) openDeleteRequestsByUser – helyes találat
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_delete_request_by_user_returns_correct_request(): void
    {
        $user = User::factory()->create();

        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'delete_account',
            'status' => 'open',
        ]);

        $req = $this->repo->openDeleteRequestsByUser($user->id);

        $this->assertNotNull($req);
        $this->assertEquals($user->id, $req->user_id);
        $this->assertEquals('delete_account', $req->type);
        $this->assertEquals('open', $req->status);
    }

    // ─────────────────────────────────────────────
    // 4) openDeleteRequestsByUser – nincs delete típusú
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_delete_request_by_user_returns_null_when_wrong_type(): void
    {
        $user = User::factory()->create();

        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'modify', // nem delete_account
            'status' => 'open',
        ]);

        $req = $this->repo->openDeleteRequestsByUser($user->id);

        $this->assertNull($req);
    }

    // ─────────────────────────────────────────────
    // 5) openDeleteRequestsByUser – nem open státusz
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_delete_request_by_user_returns_null_when_not_open_status(): void
    {
        $user = User::factory()->create();

        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'delete_account',
            'status' => 'approved', // nem open
        ]);

        $req = $this->repo->openDeleteRequestsByUser($user->id);

        $this->assertNull($req);
    }

    // ─────────────────────────────────────────────
    // 6) openDeleteRequestsByUser – ha több van: elsőt adja vissza
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_delete_request_returns_first_if_multiple(): void
    {
        $user = User::factory()->create();

        $first = UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'delete_account',
            'status' => 'open',
        ]);

        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'delete_account',
            'status' => 'open',
        ]);

        $req = $this->repo->openDeleteRequestsByUser($user->id);

        $this->assertEquals($first->id, $req->id);
    }

    // ─────────────────────────────────────────────
    // 7 – ADV: Query count (1 query várható)
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_requests_executes_single_query(): void
    {
        UserRequest::factory()->count(3)->create(['status' => 'open']);

        \DB::enableQueryLog();

        $this->repo->openRequests();

        $queries = \DB::getQueryLog();

        // 1 query = SELECT * FROM user_requests WHERE status = 'open'
        $this->assertCount(1, $queries);
    }

    // ─────────────────────────────────────────────
    // 8 – ADV: instance type ellenőrzés
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_delete_request_returns_userrequest_instance(): void
    {
        $user = User::factory()->create();

        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'delete_account',
            'status' => 'open',
        ]);

        $req = $this->repo->openDeleteRequestsByUser($user->id);

        $this->assertInstanceOf(UserRequest::class, $req);
    }

    // ─────────────────────────────────────────────
    // 9 – ADV: minden WHERE feltétel helyes
    // ─────────────────────────────────────────────

    #[Test]
    public function test_open_delete_request_applies_all_where_conditions(): void
    {
        $user = User::factory()->create();

        // 3 hibás request
        // 1) Wrong type
        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'modify',
            'status' => 'open',
        ]);

        // 2) Wrong status
        UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'delete_account',
            'status' => 'approved',
        ]);

        // 3) Different user
        $otherUser = User::factory()->create();

        UserRequest::factory()->create([
            'user_id' => $otherUser->id,
            'type' => 'delete_account',
            'status' => 'open',
        ]);

        // 1 jó request
        $valid = UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'delete_account',
            'status' => 'open',
        ]);

        $res = $this->repo->openDeleteRequestsByUser($user->id);

        $this->assertNotNull($res);
        $this->assertEquals($valid->id, $res->id);
    }
}

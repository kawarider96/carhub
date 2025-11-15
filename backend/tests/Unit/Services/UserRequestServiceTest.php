<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\UserRequest;
use App\Repositories\UserRequestRepository;
use App\Repositories\UserRepository;
use App\Services\UserRequestService;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRequestServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ───────────────────────────────────────────────
    // 1–2) all()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_all_calls_repository_all(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $requests->shouldReceive('all')->once()->andReturn(['req1', 'req2']);

        $service = new UserRequestService($requests, $users);

        $res = $service->all();

        $this->assertEquals(['req1', 'req2'], $res);
    }

    #[Test]
    public function test_all_returns_passthrough(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $expected = (object)['x' => 1];

        $requests->shouldReceive('all')->once()->andReturn($expected);

        $service = new UserRequestService($requests, $users);

        $res = $service->all();

        $this->assertSame($expected, $res);
    }

    // ───────────────────────────────────────────────
    // 3–5) createRequest()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_create_request_returns_null_if_open_request_exists(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        // open request exists
        $requests->shouldReceive('openDeleteRequestsByUser')
            ->once()
            ->with(5)
            ->andReturn((object)['id' => 1]);

        // create() MUST NOT be called
        $requests->shouldReceive('create')->never();

        $service = new UserRequestService($requests, $users);

        $res = $service->createRequest(5);

        $this->assertNull($res);
    }

    #[Test]
    public function test_create_request_creates_new_request_if_no_open_exists(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        // No open request
        $requests->shouldReceive('openDeleteRequestsByUser')
            ->once()
            ->with(7)
            ->andReturn(null);

        $requests->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['user_id'] === 7
                    && $data['type'] === 'delete_account'
                    && $data['status'] === 'open';
            }))
            ->andReturn(['id' => 88]);

        $service = new UserRequestService($requests, $users);

        $res = $service->createRequest(7);

        $this->assertEquals(['id' => 88], $res);
    }

    #[Test]
    public function test_create_request_throws_if_repository_throws(): void
    {
        $this->expectException(\Exception::class);

        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $requests->shouldReceive('openDeleteRequestsByUser')->andReturn(null);
        $requests->shouldReceive('create')->andThrow(new \Exception('DB error'));

        $service = new UserRequestService($requests, $users);

        $service->createRequest(3);
    }

    // ───────────────────────────────────────────────
    // 6–8) approve()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_approve_calls_find(): void
    {
        $request = Mockery::mock(UserRequest::class)->makePartial();
        $request->user_id = 9;

        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $requests->shouldReceive('find')->once()->with(15)->andReturn($request);

        // For state changes
        $request->shouldReceive('save')->once();

        $users->shouldReceive('delete')->once()->with(9);

        $service = new UserRequestService($requests, $users);

        $res = $service->approve(15, 100);

        $this->assertSame($request, $res);
        $this->assertEquals('approved', $request->status);
        $this->assertEquals(100, $request->handled_by);
    }

    #[Test]
    public function test_approve_updates_status_and_saves_and_deletes_user(): void
    {
        $request = Mockery::mock(UserRequest::class)->makePartial();
        $request->user_id = 22;

        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $requests->shouldReceive('find')->once()->with(2)->andReturn($request);

        $request->shouldReceive('save')->once();
        $users->shouldReceive('delete')->once()->with(22);

        $service = new UserRequestService($requests, $users);

        $res = $service->approve(2, 77);

        $this->assertSame($request, $res);
        $this->assertEquals('approved', $request->status);
        $this->assertEquals(77, $request->handled_by);
    }

    #[Test]
    public function test_approve_throws_if_save_fails(): void
    {
        $this->expectException(\Exception::class);

        $request = Mockery::mock(UserRequest::class)->makePartial();
        $request->user_id = 10;

        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $requests->shouldReceive('find')->andReturn($request);

        $request->shouldReceive('save')->andThrow(new \Exception('save error'));

        // should never reach deleting
        $users->shouldReceive('delete')->never();

        $service = new UserRequestService($requests, $users);

        $service->approve(1, 1);
    }

    // ───────────────────────────────────────────────
    // 9–10) reject()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_reject_calls_find_and_updates_status(): void
    {
        $request = Mockery::mock(UserRequest::class)->makePartial();

        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $requests->shouldReceive('find')->once()->with(5)->andReturn($request);

        $request->shouldReceive('save')->once();

        // MUST NOT delete user!
        $users->shouldReceive('delete')->never();

        $service = new UserRequestService($requests, $users);

        $res = $service->reject(5, 999);

        $this->assertSame($request, $res);
        $this->assertEquals('rejected', $request->status);
        $this->assertEquals(999, $request->handled_by);
    }

    // ───────────────────────────────────────────────
    // 11–14) Advanced
    // ───────────────────────────────────────────────

    #[Test]
    public function test_reject_return_passthrough(): void
    {
        $request = Mockery::mock(UserRequest::class)->makePartial();

        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $requests->shouldReceive('find')->andReturn($request);
        $request->shouldReceive('save')->once();

        $service = new UserRequestService($requests, $users);

        $res = $service->reject(1, 20);

        $this->assertSame($request, $res);
    }

    #[Test]
    public function test_create_request_data_structure_correct(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users = Mockery::mock(UserRepository::class);

        $requests->shouldReceive('openDeleteRequestsByUser')->andReturn(null);

        $requests->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['user_id'] === 100
                    && $data['type'] === 'delete_account'
                    && $data['status'] === 'open';
            }))
            ->andReturn(['id' => 555]);

        $service = new UserRequestService($requests, $users);

        $res = $service->createRequest(100);

        $this->assertEquals(['id' => 555], $res);
    }
}

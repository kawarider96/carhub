<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\UserRequest;
use App\Repositories\UserRepository;
use App\Repositories\UserRequestRepository;
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

    #[Test]
    public function test_open_returns_open_requests(): void
    {
        $reqRepo = Mockery::mock(UserRequestRepository::class);

        // itt lehet stdClass, mert csak collection-t számolunk, nem Service type-hint
        $reqRepo->shouldReceive('open')
            ->once()
            ->andReturn(collect([(object)['id' => 1]]));

        $userRepo = Mockery::mock(UserRepository::class);

        $service = new UserRequestService($reqRepo, $userRepo);
        $res = $service->open();

        $this->assertCount(1, $res);
    }

    #[Test]
    public function test_user_request_fetches_by_user(): void
    {
        $request = new UserRequest();
        $request->id = 10;
        $request->user_id = 5;
        $request->type = 'delete_account';
        $request->status = 'open';

        $reqRepo = Mockery::mock(UserRequestRepository::class);
        $reqRepo->shouldReceive('byUser')
            ->once()
            ->with(5)
            ->andReturn($request);

        $userRepo = Mockery::mock(UserRepository::class);

        $service = new UserRequestService($reqRepo, $userRepo);

        $res = $service->userRequest(5);

        $this->assertEquals(10, $res->id);
    }

    #[Test]
    public function test_create_request_returns_exists_when_already_present(): void
    {
        $existing = new UserRequest();
        $existing->id = 1;
        $existing->user_id = 7;
        $existing->type = 'delete_account';
        $existing->status = 'open';

        $reqRepo = Mockery::mock(UserRequestRepository::class);
        $reqRepo->shouldReceive('byUser')
            ->once()
            ->with(7)
            ->andReturn($existing);

        $userRepo = Mockery::mock(UserRepository::class);

        $service = new UserRequestService($reqRepo, $userRepo);

        $res = $service->createRequest(7);

        $this->assertFalse($res['status']);
        $this->assertEquals('exists', $res['error']);
    }

    #[Test]
    public function test_create_request_creates_when_not_present(): void
    {
        $created = new UserRequest();
        $created->id = 5;
        $created->user_id = 8;
        $created->type = 'delete_account';
        $created->status = 'open';

        $reqRepo = Mockery::mock(UserRequestRepository::class);

        $reqRepo->shouldReceive('byUser')
            ->once()
            ->with(8)
            ->andReturn(null);

        $reqRepo->shouldReceive('create')
            ->once()
            ->with(['user_id' => 8, 'status' => 'open'])
            ->andReturn($created);

        $userRepo = Mockery::mock(UserRepository::class);

        $service = new UserRequestService($reqRepo, $userRepo);

        $res = $service->createRequest(8);

        $this->assertEquals(5, $res->id);
    }

    #[Test]
    public function test_approve_sets_status_and_deletes_user(): void
    {
        $request = new class {
            public int $id = 5;
            public int $user_id = 22;
            public string $status = 'open';
            public ?int $handled_by = null;
            public int $saved = 0;

            public function save(): void { $this->saved++; }
        };

        $reqRepo = Mockery::mock(UserRequestRepository::class);
        $reqRepo->shouldReceive('find')->once()->with(5)->andReturn($request);

        $userRepo = Mockery::mock(UserRepository::class);
        $userRepo->shouldReceive('delete')->once()->with(22)->andReturnTrue();

        $service = new UserRequestService($reqRepo, $userRepo);

        $res = $service->approve(5, 1);

        $this->assertEquals('approved', $request->status);
        $this->assertEquals(1, $request->handled_by);
        $this->assertEquals(1, $request->saved);
        $this->assertSame($request, $res);
    }

    #[Test]
    public function test_reject_sets_status_rejected(): void
    {
        $request = new class {
            public string $status = 'open';
            public ?int $handled_by = null;
            public int $saved = 0;

            public function save(): void { $this->saved++; }
        };

        $reqRepo = Mockery::mock(UserRequestRepository::class);
        $reqRepo->shouldReceive('find')->once()->with(9)->andReturn($request);

        $userRepo = Mockery::mock(UserRepository::class);

        $service = new UserRequestService($reqRepo, $userRepo);

        $res = $service->reject(9, 3);

        $this->assertEquals('rejected', $request->status);
        $this->assertEquals(3, $request->handled_by);
        $this->assertEquals(1, $request->saved);
        $this->assertSame($request, $res);
    }
}

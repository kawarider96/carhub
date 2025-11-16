<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\UserRequest;
use App\Repositories\UserRequestRepository;
use App\Repositories\UserRepository;
use App\Repositories\CarBrandRepository;
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

    private function makeService(
        $requestsMock,
        $usersMock,
        $brandsMock
    ): UserRequestService {
        return new UserRequestService($requestsMock, $usersMock, $brandsMock);
    }

    // ───────────────────────────────────────────────
    // ALL()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_all_delegates_to_repository(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users    = Mockery::mock(UserRepository::class);
        $brands   = Mockery::mock(CarBrandRepository::class);

        $requests->shouldReceive('all')->once()->andReturn(['a','b']);

        $service = $this->makeService($requests, $users, $brands);

        $res = $service->all();

        $this->assertEquals(['a','b'], $res);
    }

    // ───────────────────────────────────────────────
    // CREATE REQUEST
    // ───────────────────────────────────────────────

    #[Test]
    public function test_create_request_returns_false_if_open_exists(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users    = Mockery::mock(UserRepository::class);
        $brands   = Mockery::mock(CarBrandRepository::class);

        $requests->shouldReceive('findOpenByUserAndType')
            ->once()
            ->with(10, 'delete_account')
            ->andReturn((object)['id'=>1]);

        $requests->shouldReceive('create')->never();

        $service = $this->makeService($requests, $users, $brands);

        $this->assertFalse(
            $service->createRequest(10, 'delete_account', [])
        );
    }

    #[Test]
    public function test_create_request_creates_when_none_exists(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users    = Mockery::mock(UserRepository::class);
        $brands   = Mockery::mock(CarBrandRepository::class);

        $requests->shouldReceive('findOpenByUserAndType')
            ->once()
            ->with(5, 'delete_account')
            ->andReturn(null);

        $requests->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['user_id'] === 5 &&
                       $data['type']     === 'delete_account' &&
                       $data['status']   === 'open';
            }))
            ->andReturn(['id'=>999]);

        $service = $this->makeService($requests, $users, $brands);

        $res = $service->createRequest(5, 'delete_account', []);

        $this->assertEquals(['id'=>999], $res);
    }

    // ───────────────────────────────────────────────
    // APPROVE()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_approve_handles_delete_account(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users    = Mockery::mock(UserRepository::class);
        $brands   = Mockery::mock(CarBrandRepository::class);

        $request = Mockery::mock(UserRequest::class)->makePartial();
        $request->type = 'delete_account';
        $request->user_id = 7;

        $requests->shouldReceive('find')
            ->once()->with(2)->andReturn($request);

        $users->shouldReceive('delete')->once()->with(7);

        $request->shouldReceive('save')->once();

        $service = $this->makeService($requests, $users, $brands);

        $res = $service->approve(2, 100);

        $this->assertEquals('approved', $request->status);
        $this->assertEquals(100, $request->handled_by);
        $this->assertSame($request, $res);
    }

    #[Test]
    public function test_approve_handles_missing_brand(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users    = Mockery::mock(UserRepository::class);
        $brands   = Mockery::mock(CarBrandRepository::class);

        $request = Mockery::mock(UserRequest::class)->makePartial();
        $request->type = 'missing_brand';
        $request->user_id = 15;
        $request->payload = ['brand' => 'Lamborghini'];

        $requests->shouldReceive('find')
            ->once()->with(9)->andReturn($request);

        // brand NEM létezik
        $brands->shouldReceive('existsByName')
            ->once()->with('Lamborghini')->andReturn(false);

        $brands->shouldReceive('create')
            ->once()->with(['name'=>'Lamborghini']);

        $request->shouldReceive('save')->once();

        $service = $this->makeService($requests, $users, $brands);

        $res = $service->approve(9, 200);

        $this->assertEquals('approved', $request->status);
        $this->assertEquals(200, $request->handled_by);
        $this->assertSame($request, $res);
    }

    // ───────────────────────────────────────────────
    // REJECT()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_reject_updates_status(): void
    {
        $requests = Mockery::mock(UserRequestRepository::class);
        $users    = Mockery::mock(UserRepository::class);
        $brands   = Mockery::mock(CarBrandRepository::class);

        $request = Mockery::mock(UserRequest::class)->makePartial();

        $requests->shouldReceive('find')
            ->once()->with(20)->andReturn($request);

        $request->shouldReceive('save')->once();

        $service = $this->makeService($requests, $users, $brands);

        $res = $service->reject(20, 300);

        $this->assertEquals('rejected', $request->status);
        $this->assertEquals(300, $request->handled_by);
        $this->assertSame($request, $res);
    }
}

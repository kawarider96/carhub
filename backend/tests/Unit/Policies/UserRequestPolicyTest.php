<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\UserRequest;
use App\Policies\UserRequestPolicy;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRequestPolicyTest extends TestCase
{
    private function makeUser(int $id, string $role = 'user', bool $active = true): User
    {
        $u = new User();
        $u->id = $id;
        $u->role = $role;
        $u->is_active = $active;
        return $u;
    }

    private function makeRequest(int $id, int $userId): UserRequest
    {
        $r = new UserRequest();
        $r->id = $id;
        $r->user_id = $userId;
        $r->status = 'OPEN';
        return $r;
    }

    #[Test]
    public function test_view_any_only_admin(): void
    {
        $policy = new UserRequestPolicy();
        $admin = $this->makeUser(1, 'admin');
        $user  = $this->makeUser(2, 'user');

        $this->assertTrue($policy->viewAny($admin));
        $this->assertFalse($policy->viewAny($user));
    }

    #[Test]
    public function test_view_admin_or_owner(): void
    {
        $policy = new UserRequestPolicy();
        $admin = $this->makeUser(1, 'admin');
        $alice = $this->makeUser(2, 'user');
        $bob   = $this->makeUser(3, 'user');
        $req   = $this->makeRequest(10, $alice->id);

        $this->assertTrue($policy->view($admin, $req));
        $this->assertTrue($policy->view($alice, $req));
        $this->assertFalse($policy->view($bob, $req));
    }

    #[Test]
    public function test_create_only_active_user(): void
    {
        $policy = new UserRequestPolicy();
        $active = $this->makeUser(2, 'user', true);
        $inactive = $this->makeUser(3, 'user', false);

        $this->assertTrue($policy->create($active));
        $this->assertFalse($policy->create($inactive));
    }

    #[Test]
    public function test_update_delete_approve_reject_only_admin(): void
    {
        $policy = new UserRequestPolicy();
        $admin = $this->makeUser(1, 'admin');
        $user  = $this->makeUser(2, 'user');
        $req   = $this->makeRequest(10, $user->id);

        $this->assertTrue($policy->update($admin, $req));
        $this->assertTrue($policy->delete($admin, $req));
        $this->assertTrue($policy->approve($admin, $req));
        $this->assertTrue($policy->reject($admin, $req));

        $this->assertFalse($policy->update($user, $req));
        $this->assertFalse($policy->delete($user, $req));
        $this->assertFalse($policy->approve($user, $req));
        $this->assertFalse($policy->reject($user, $req));
    }
}


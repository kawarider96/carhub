<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    private function makeUser(int $id, string $role = 'user', bool $active = true): User
    {
        $u = new User();
        $u->id = $id;
        $u->role = $role;
        $u->is_active = $active;
        $u->failed_logins = 0;
        return $u;
    }

    #[Test]
    public function test_view_any_only_admin(): void
    {
        $policy = new UserPolicy();
        $admin = $this->makeUser(1, 'admin');
        $user  = $this->makeUser(2, 'user');

        $this->assertTrue($policy->viewAny($admin));
        $this->assertFalse($policy->viewAny($user));
    }

    #[Test]
    public function test_view_self_or_admin(): void
    {
        $policy = new UserPolicy();
        $admin = $this->makeUser(1, 'admin');
        $alice = $this->makeUser(2, 'user');
        $bob   = $this->makeUser(3, 'user');

        $this->assertTrue($policy->view($admin, $alice));
        $this->assertTrue($policy->view($alice, $alice));
        $this->assertFalse($policy->view($alice, $bob));
    }

    #[Test]
    public function test_create_only_admin(): void
    {
        $policy = new UserPolicy();
        $admin = $this->makeUser(1, 'admin');
        $user  = $this->makeUser(2, 'user');

        $this->assertTrue($policy->create($admin));
        $this->assertFalse($policy->create($user));
    }

    #[Test]
    public function test_update_self_or_admin(): void
    {
        $policy = new UserPolicy();
        $admin = $this->makeUser(1, 'admin');
        $alice = $this->makeUser(2, 'user');
        $bob   = $this->makeUser(3, 'user');

        $this->assertTrue($policy->update($admin, $alice));
        $this->assertTrue($policy->update($alice, $alice));
        $this->assertFalse($policy->update($alice, $bob));
    }

    #[Test]
    public function test_delete_only_admin_and_never_self(): void
    {
        $policy = new UserPolicy();
        $admin = $this->makeUser(1, 'admin');
        $alice = $this->makeUser(2, 'user');

        // admin cannot delete self
        $this->assertFalse($policy->delete($admin, $admin));
        // admin can delete others
        $this->assertTrue($policy->delete($admin, $alice));
        // user can never delete
        $this->assertFalse($policy->delete($alice, $admin));
        $this->assertFalse($policy->delete($alice, $alice));
    }

    #[Test]
    public function test_lock_and_unlock_only_admin(): void
    {
        $policy = new UserPolicy();
        $admin = $this->makeUser(1, 'admin');
        $user  = $this->makeUser(2, 'user');

        $this->assertTrue($policy->lock($admin, $user));
        $this->assertTrue($policy->unlock($admin, $user));
        $this->assertFalse($policy->lock($user, $admin));
        $this->assertFalse($policy->unlock($user, $admin));
    }
}


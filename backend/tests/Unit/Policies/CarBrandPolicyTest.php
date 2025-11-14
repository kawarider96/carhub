<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\CarBrand;
use App\Models\User;
use App\Policies\CarBrandPolicy;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarBrandPolicyTest extends TestCase
{
    private function makeUser(int $id, string $role = 'user'): User
    {
        $u = new User();
        $u->id = $id;
        $u->role = $role;
        return $u;
    }

    private function makeBrand(int $id): CarBrand
    {
        $b = new CarBrand();
        $b->id = $id;
        $b->name = 'Brand';
        return $b;
    }

    #[Test]
    public function test_view_any_only_admin(): void
    {
        $policy = new CarBrandPolicy();
        $admin = $this->makeUser(1, 'admin');
        $user  = $this->makeUser(2, 'user');

        $this->assertTrue($policy->viewAny($admin));
        $this->assertFalse($policy->viewAny($user));
    }

    #[Test]
    public function test_view_single_brand_anyone(): void
    {
        $policy = new CarBrandPolicy();
        $user = $this->makeUser(2, 'user');
        $brand = $this->makeBrand(10);
        $this->assertTrue($policy->view($user, $brand));
        // even unauthenticated (null) allowed by signature, but our method requires User, so skip
    }

    #[Test]
    public function test_modify_only_admin(): void
    {
        $policy = new CarBrandPolicy();
        $admin = $this->makeUser(1, 'admin');
        $user  = $this->makeUser(2, 'user');
        $brand = $this->makeBrand(10);

        $this->assertTrue($policy->create($admin));
        $this->assertTrue($policy->update($admin, $brand));
        $this->assertTrue($policy->delete($admin, $brand));

        $this->assertFalse($policy->create($user));
        $this->assertFalse($policy->update($user, $brand));
        $this->assertFalse($policy->delete($user, $brand));
    }
}


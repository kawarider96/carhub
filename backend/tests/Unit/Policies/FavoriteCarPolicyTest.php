<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\FavoriteCar;
use App\Models\User;
use App\Policies\FavoriteCarPolicy;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoriteCarPolicyTest extends TestCase
{
    private function makeUser(int $id, string $role = 'user', bool $active = true): User
    {
        $u = new User();
        $u->id = $id;
        $u->role = $role;
        $u->is_active = $active;
        return $u;
    }

    private function makeFav(int $id, int $ownerId): FavoriteCar
    {
        $f = new FavoriteCar();
        $f->id = $id;
        $f->user_id = $ownerId;
        return $f;
    }

    #[Test]
    public function test_view_any_only_admin(): void
    {
        $policy = new FavoriteCarPolicy();
        $admin = $this->makeUser(1, 'admin');
        $user  = $this->makeUser(2, 'user');

        $this->assertTrue($policy->viewAny($admin));
        $this->assertFalse($policy->viewAny($user));
    }

    #[Test]
    public function test_view_admin_or_owner(): void
    {
        $policy = new FavoriteCarPolicy();
        $admin = $this->makeUser(1, 'admin');
        $alice = $this->makeUser(2, 'user');
        $car   = $this->makeFav(10, $alice->id);
        $bob   = $this->makeUser(3, 'user');

        $this->assertTrue($policy->view($admin, $car));
        $this->assertTrue($policy->view($alice, $car));
        $this->assertFalse($policy->view($bob, $car));
    }

    #[Test]
    public function test_create_only_active_user(): void
    {
        $policy = new FavoriteCarPolicy();
        $activeUser   = $this->makeUser(2, 'user', true);
        $inactiveUser = $this->makeUser(3, 'user', false);

        $this->assertTrue($policy->create($activeUser));
        $this->assertFalse($policy->create($inactiveUser));
    }

    #[Test]
    public function test_update_and_delete_only_owner_not_admin(): void
    {
        $policy = new FavoriteCarPolicy();
        $admin = $this->makeUser(1, 'admin');
        $owner = $this->makeUser(2, 'user');
        $other = $this->makeUser(3, 'user');
        $car   = $this->makeFav(10, $owner->id);

        $this->assertTrue($policy->update($owner, $car));
        $this->assertFalse($policy->update($admin, $car));
        $this->assertFalse($policy->update($other, $car));

        $this->assertTrue($policy->delete($owner, $car));
        $this->assertFalse($policy->delete($admin, $car));
        $this->assertFalse($policy->delete($other, $car));
    }
}


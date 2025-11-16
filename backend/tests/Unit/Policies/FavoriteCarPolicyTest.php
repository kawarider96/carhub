<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\FavoriteCar;
use App\Policies\FavoriteCarPolicy;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoriteCarPolicyTest extends TestCase
{
    protected FavoriteCarPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new FavoriteCarPolicy();
    }

    private function makeUser(int $id, string $role): User
    {
        $u = new User();
        $u->id = $id;
        $u->role = $role;
        return $u;
    }

    private function makeCar(int $userId): FavoriteCar
    {
        $c = new FavoriteCar();
        $c->user_id = $userId;
        return $c;
    }

    //──────────────────────────────────────
    // viewAny
    //──────────────────────────────────────
    #[Test]
    public function test_view_any_allows_admin()
    {
        $admin = $this->makeUser(1, 'admin');

        $this->assertTrue(
            $this->policy->viewAny($admin)
        );
    }

    #[Test]
    public function test_view_any_denies_non_admin()
    {
        $user = $this->makeUser(2, 'user');

        $this->assertFalse(
            $this->policy->viewAny($user)
        );
    }

    //──────────────────────────────────────
    // view
    //──────────────────────────────────────
    #[Test]
    public function test_view_allows_admin_for_any_car()
    {
        $admin = $this->makeUser(1, 'admin');
        $car = $this->makeCar(999);

        $this->assertTrue(
            $this->policy->view($admin, $car)
        );
    }

    #[Test]
    public function test_view_allows_owner()
    {
        $user = $this->makeUser(10, 'user');
        $car = $this->makeCar(10);

        $this->assertTrue(
            $this->policy->view($user, $car)
        );
    }

    #[Test]
    public function test_view_denies_non_owner_non_admin()
    {
        $user = $this->makeUser(10, 'user');
        $car = $this->makeCar(20);

        $this->assertFalse(
            $this->policy->view($user, $car)
        );
    }

    //──────────────────────────────────────
    // update
    //──────────────────────────────────────
    #[Test]
    public function test_update_allows_owner()
    {
        $user = $this->makeUser(10, 'user');
        $car = $this->makeCar(10);

        $this->assertTrue(
            $this->policy->update($user, $car)
        );
    }

    #[Test]
    public function test_update_denies_admin_if_not_owner()
    {
        $admin = $this->makeUser(1, 'admin');
        $car = $this->makeCar(10);

        $this->assertFalse(
            $this->policy->update($admin, $car)
        );
    }

    #[Test]
    public function test_update_denies_non_owner()
    {
        $user = $this->makeUser(5, 'user');
        $car = $this->makeCar(99);

        $this->assertFalse(
            $this->policy->update($user, $car)
        );
    }

    //──────────────────────────────────────
    // create
    //──────────────────────────────────────
    #[Test]
    public function test_create_allows_owner()
    {
        $user = $this->makeUser(10, 'user');
        $car = $this->makeCar(10);

        $this->assertTrue(
            $this->policy->create($user, $car)
        );
    }

    #[Test]
    public function test_create_denies_admin_if_not_owner()
    {
        $admin = $this->makeUser(1, 'admin');
        $car = $this->makeCar(10);

        $this->assertFalse(
            $this->policy->create($admin, $car)
        );
    }

    #[Test]
    public function test_create_denies_non_owner()
    {
        $user = $this->makeUser(3, 'user');
        $car = $this->makeCar(7);

        $this->assertFalse(
            $this->policy->create($user, $car)
        );
    }

    //──────────────────────────────────────
    // delete
    //──────────────────────────────────────
    #[Test]
    public function test_delete_allows_owner()
    {
        $user = $this->makeUser(10, 'user');
        $car = $this->makeCar(10);

        $this->assertTrue(
            $this->policy->delete($user, $car)
        );
    }

    #[Test]
    public function test_delete_denies_admin_if_not_owner()
    {
        $admin = $this->makeUser(1, 'admin');
        $car = $this->makeCar(20);

        $this->assertFalse(
            $this->policy->delete($admin, $car)
        );
    }

    #[Test]
    public function test_delete_denies_non_owner()
    {
        $user = $this->makeUser(10, 'user');
        $car = $this->makeCar(30);

        $this->assertFalse(
            $this->policy->delete($user, $car)
        );
    }
}

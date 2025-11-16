<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    protected UserPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new UserPolicy();
    }

    private function makeUser(int $id): User
    {
        $u = new User();
        $u->id = $id;
        return $u;
    }

    //────────────────────────────────────────────
    // UPDATE – saját fiók módosítása engedélyezett
    //────────────────────────────────────────────
    #[Test]
    public function test_update_allows_modifying_self(): void
    {
        $user = $this->makeUser(10);

        $this->assertTrue(
            $this->policy->update($user, $user)
        );
    }

    //────────────────────────────────────────────
    // UPDATE – más felhasználó módosítása tiltott
    //────────────────────────────────────────────
    #[Test]
    public function test_update_denies_modifying_other_user(): void
    {
        $user = $this->makeUser(10);
        $target = $this->makeUser(20);

        $this->assertFalse(
            $this->policy->update($user, $target)
        );
    }
}

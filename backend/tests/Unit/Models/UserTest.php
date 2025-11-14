<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\FavoriteCar;
use App\Models\UserRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_user(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id'        => $user->id,
            'username'  => $user->username,
        ]);
    }

    #[Test]
    public function password_is_hashed(): void
    {
        $plain = 'Secret123!';
        $user = User::factory()->create([
            'password' => $plain
        ]);

        $this->assertNotEquals($plain, $user->password);

        $this->assertTrue(password_verify($plain, $user->password));
    }

    #[Test]                                                                                                                             
    public function password_is_not_rehashed_if_already_hashed(): void                                                                  
    {                                                                                                                                   
    $plain = 'Secret123!';                                                                                                              
    $hashed = password_hash($plain, PASSWORD_BCRYPT);                                                                                   
                                                                                                                                        
        $user = User::factory()->create([                                                                                               
            'password' => $hashed,                                                                                                      
        ]);                                                                                                                             
                                                                                                                                        
        $this->assertEquals($hashed, $user->password);                                                                                  
        $this->assertTrue(password_verify($plain, $user->password));                                                                    
        $this->assertStringStartsWith('$2y$', $user->password);                                                                         
        $this->assertEquals(60, strlen($user->password));                                                                               
                                                                                                                                        
    }                   
    
    #[Test]
    public function it_knows_if_user_is_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user  = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    #[Test]
    public function it_knows_if_user_is_locked(): void
    {
        $locked = User::factory()->create([
            'is_active' => false
        ]);

        $notLocked = User::factory()->create([
            'is_active' => true
        ]);

        $this->assertTrue($locked->isLocked());
        $this->assertFalse($notLocked->isLocked());
    }

    #[Test]
    public function it_has_favorite_cars(): void
    {
        $user = User::factory()->create();

        FavoriteCar::factory()->count(2)->create([
            'user_id' => $user->id
        ]);

        $this->assertCount(2, $user->favoriteCars);
    }

    #[Test]
    public function it_has_user_requests(): void
    {
        $user = User::factory()->create();

        UserRequest::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $this->assertCount(3, $user->requests);
    }

    #[Test]
    public function it_has_handled_requests(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        UserRequest::factory()->count(4)->create([
            'handled_by' => $admin->id
        ]);

        $this->assertCount(4, $admin->handledRequests);
    }

    #[Test]
    public function it_casts_dates_correctly(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Carbon\CarbonImmutable::class, $user->created_at);
        $this->assertInstanceOf(\Carbon\CarbonImmutable::class, $user->updated_at);
    }
}

<?php

namespace Tests\Unit\Repositories;

use App\Models\CarBrand;
use App\Repositories\CarBrandRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CarBrandRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CarBrandRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CarBrandRepository(new CarBrand);
    }

    #[Test]
    public function test_create_brand()
    {
        $brand = $this->repo->create(['name' => 'BMW']);

        $this->assertDatabaseHas('car_brands', [
            'name' => 'BMW'
        ]);
    }

    #[Test]                                                                                                                             
    public function test_exists_by_name_true_when_present()                                                                             
    {
        CarBrand::factory()->create(['name' => 'Toyota']);                                                                                  
                                                                                                                                        
        $this->assertTrue($this->repo->existsByName('Toyota'));                                                                         
                                                                                                                                        
    }                                                                                                                                   
                                                                                                                                        
    #[Test]                                                                                                                             
    public function test_exists_by_name_false_when_absent()                                                                             
    {                                                                                                                                   
        $this->assertFalse($this->repo->existsByName('NonExistingBrand'));                                                                  
    }

    #[Test]
    public function test_find_existing_brand_by_name()
    {
        CarBrand::factory()->create(['name' => 'Audi']);

        $result = $this->repo->findByName('Audi');

        $this->assertNotNull($result);
        $this->assertEquals('Audi', $result->name);
    }
}

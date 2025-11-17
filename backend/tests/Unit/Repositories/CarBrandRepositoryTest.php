<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\CarBrand;
use App\Repositories\CarBrandRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarBrandRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CarBrandRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CarBrandRepository(new CarBrand());
    }

    // ─────────────────────────────────────────────
    // 1–2) existsByName()
    // ─────────────────────────────────────────────

    #[Test]
    public function test_exists_by_name_returns_true_when_brand_exists(): void
    {
        CarBrand::factory()->create(['name' => 'BMW']);

        $result = $this->repo->existsByName('BMW');

        $this->assertTrue($result);
    }

    #[Test]
    public function test_exists_by_name_returns_false_when_brand_missing(): void
    {
        $result = $this->repo->existsByName('MissingBrand');

        $this->assertFalse($result);
    }


    // ─────────────────────────────────────────────
    // 3–4) findByName()
    // ─────────────────────────────────────────────

    #[Test]
    public function test_find_by_name_returns_brand(): void
    {
        CarBrand::factory()->create(['name' => 'Audi']);

        $brand = $this->repo->findByName('Audi');

        $this->assertNotNull($brand);
        $this->assertEquals('Audi', $brand->name);
    }

    #[Test]
    public function test_find_by_name_returns_null_if_not_found(): void
    {
        $brand = $this->repo->findByName('NonExisting');

        $this->assertNull($brand);
    }


    // ─────────────────────────────────────────────
    // 5–6) Case sensitivity behaviour (DB collation dependent)
    // ─────────────────────────────────────────────

    #[Test]
    public function test_exists_by_name_case_insensitive_match(): void
    {
        // Laravel default: utf8mb4_unicode_ci = case-insensitive
        CarBrand::factory()->create(['name' => 'TOYOTA']);

        $result = $this->repo->existsByName('toyota');

        $this->assertTrue($result);
    }

    #[Test]
    public function test_find_by_name_case_insensitive_match(): void
    {
        CarBrand::factory()->create(['name' => 'Mercedes']);

        $brand = $this->repo->findByName('mercedes');

        $this->assertNotNull($brand);
        $this->assertEquals('Mercedes', $brand->name);
    }


    // ─────────────────────────────────────────────
    // 7–8) Optional: type check & query count
    // ─────────────────────────────────────────────

    #[Test]
    public function test_find_by_name_returns_carbrand_instance(): void
    {
        CarBrand::factory()->create(['name' => 'Kia']);

        $brand = $this->repo->findByName('Kia');

        $this->assertInstanceOf(CarBrand::class, $brand);
    }

    #[Test]
    public function test_exists_by_name_uses_single_query(): void
    {
        CarBrand::factory()->create(['name' => 'Ford']);

        \DB::enableQueryLog();

        $this->repo->existsByName('Ford');

        $queries = \DB::getQueryLog();

        $this->assertCount(1, $queries, 'existsByName should only execute 1 query');
    }
}

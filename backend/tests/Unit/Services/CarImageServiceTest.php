<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Repositories\CarImageRepository;
use App\Services\CarImageService;
use Illuminate\Http\UploadedFile;
use Mockery;
use App\Models\CarImage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarImageServiceTest extends TestCase
{
    private CarImageRepository $repo;
    private CarImageService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = Mockery::mock(CarImageRepository::class);
        $this->service = new CarImageService($this->repo);
    }

    // ───────────────────────────────────────────────
    // getByFavoriteCar
    // ───────────────────────────────────────────────

    #[Test]
    public function it_returns_images_by_favorite_car(): void
    {
        $fakeResult = ['img1', 'img2'];

        $this->repo
            ->shouldReceive('getByFavoriteCar')
            ->once()
            ->with(10)
            ->andReturn($fakeResult);

        $result = $this->service->getByFavoriteCar(10);

        $this->assertSame($fakeResult, $result);
    }

    // ───────────────────────────────────────────────
    // uploadImages
    // ───────────────────────────────────────────────

    #[Test]
    public function it_uploads_multiple_images(): void
    {
        // Create simple fake files (GD not required)
        $file1 = UploadedFile::fake()->create('car1.jpg', 10, 'image/jpeg');
        $file2 = UploadedFile::fake()->create('car2.jpg', 15, 'image/jpeg');

        $favoriteCarId = 99;

        // Read actual contents from temp files
        $content1 = file_get_contents($file1->getRealPath());
        $content2 = file_get_contents($file2->getRealPath());

        // Mocked CarImage model objects (return type must be CarImage)
        $created1 = new CarImage();
        $created1->id = 1;
        $created1->mime = 'image/jpeg';

        $created2 = new CarImage();
        $created2->id = 2;
        $created2->mime = 'image/jpeg';

        // Expectations for repository::create()
        $this->repo
            ->shouldReceive('create')
            ->once()
            ->with([
                'favorite_car_id' => $favoriteCarId,
                'content'         => $content1,
                'mime'            => 'image/jpeg',
            ])
            ->andReturn($created1);

        $this->repo
            ->shouldReceive('create')
            ->once()
            ->with([
                'favorite_car_id' => $favoriteCarId,
                'content'         => $content2,
                'mime'            => 'image/jpeg',
            ])
            ->andReturn($created2);

        // Call the service
        $result = $this->service->uploadImages($favoriteCarId, [$file1, $file2]);

        // Assertions
        $this->assertCount(2, $result);
        $this->assertSame($created1, $result[0]);
        $this->assertSame($created2, $result[1]);
    }

    // ───────────────────────────────────────────────
    // delete
    // ───────────────────────────────────────────────

    #[Test]
    public function it_deletes_image(): void
    {
        $this->repo
            ->shouldReceive('delete')
            ->once()
            ->with(5)
            ->andReturn(true);

        $result = $this->service->delete(5);

        $this->assertTrue($result);
    }
}

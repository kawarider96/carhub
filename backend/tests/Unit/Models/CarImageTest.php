<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\FavoriteCar;
use App\Models\CarImage;
use PHPUnit\Framework\Attributes\Test;

class CarImageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_car_image()
    {
        $fav = FavoriteCar::factory()->create();

        $image = CarImage::factory()->create([
            'favorite_car_id' => $fav->id,
            'content' => 'binarytestdata'
        ]);

        $this->assertDatabaseHas('car_images', [
            'favorite_car_id' => $fav->id
        ]);
    }

    #[Test]                                                                                                                             
    public function it_stores_mime_type()                                                                                               
    {                                                                                                                                   
        $image = CarImage::factory()->create([                                                                                              
            'mime' => 'image/png',                                                                                                              
        ]);                                                                                                                                 
                                                                                                                                        
        $this->assertEquals('image/png', $image->mime);                                                                                 
                                                                                                                                        
    }

    #[Test]
    public function it_belongs_to_a_favorite_car()
    {
        $fav = FavoriteCar::factory()->create();

        $image = CarImage::factory()->create([
            'favorite_car_id' => $fav->id
        ]);

        $this->assertInstanceOf(FavoriteCar::class, $image->favoriteCar);
        $this->assertEquals($fav->id, $image->favoriteCar->id);
    }

    #[Test]
    public function image_data_is_stored_as_string_or_binary()
    {
        $image = CarImage::factory()->create([
            'content' => 'testbinary'
        ]);

        $this->assertIsString($image->content);
    }

    #[Test]
    public function timestamps_are_carbon_instances()
    {
        $image = CarImage::factory()->create();
        
        $this->assertInstanceOf(\Carbon\CarbonImmutable::class, $image->created_at);
        $this->assertInstanceOf(\Carbon\CarbonImmutable::class, $image->updated_at);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_car_create()
    {
        $car = Car::factory(1)->create();

        self::assertNotEmpty($car);
    }


    public function test_attachCar()
    {
        $car_id = Car::factory()->create()->id;

        $user_id = User::factory()->create()->id;

        $user = User::attachCar($user_id,$car_id);

        self::assertTrue($user);
    }

}

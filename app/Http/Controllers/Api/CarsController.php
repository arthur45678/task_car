<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/cars",
     *   tags={"Cars"},
     *   @OA\Response(response="200",description="Search car by params",),
     *   @OA\Parameter(name="title",description="Car name",in="path",required=true,@OA\Schema(type="string")),
     * ),
     */
    public function index(Request $request)
    {
        $car = Car::latest()->paginate(25);

        return $car;
    }

    /**
     * @OA\Post(
     *   path="/api/cars",
     *   tags={"Cars"},
     *   @OA\Response(response="201",description="Store car",),
     *   @OA\Parameter(name="title",description="Car name",in="path",required=true,@OA\Schema(type="string")),
     * ),
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:10',
        ]);

        $car = Car::addCar($request->title);

        return response()->json($car, 201);
    }

    /**
     * @OA\Get(
     *   path="/api/cars/{id}",
     *   tags={"Cars"},
     *   @OA\Response(response="200",description="car id",),
     * ),
     */
    public function show($id)
    {

        $item = Car::find($id);
        if(!$item){
            return response()->json(['message' => 'Not found'],404);
        }
        return $item;
    }

    /**
     * @OA\Put(
     *   path="/api/cars",
     *   tags={"Cars"},
     *   @OA\Response(response="200",description="Update car",),
     *   @OA\Parameter(name="title",description="car name",in="path",required=true,@OA\Schema(type="string")),
     * ),
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:10'
        ]);
        $item = self::find($id);
        if(!$item){
            return response()->json(['message' => 'Not found'],404);
        }
        return $item;
    }

    /**
     * @OA\Delete(
     *   path="/api/cars/{id}",
     *   tags={"Cars"},
     *   @OA\Response(response="204",description="Delete car",),
     *   @OA\Parameter(name="id",description="car id",in="path",required=true,@OA\Schema(type="integer")),
     * ),
     */
    public function destroy($id)
    {
        if(Car::find($id)->user()->exists()){
            return response()->json(['message' => 'car cannot be deleted, has products'],404);
        }
        Car::destroy($id);

        return response()->json(null, 204);
    }
}

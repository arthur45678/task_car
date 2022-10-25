<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/users",
     *   tags={"Users"},
     *   @OA\Response(response="200",description="Search car by params",),
     *   @OA\Parameter(name="title",description="car name",in="path",required=true,@OA\Schema(type="string")),
     * ),
     */
    public function index(Request $request)
    {
        $user = User::latest()->paginate(25);

        return $user;
    }

    /**
     * @OA\Post(
     *   path="/api/users/assign-car",
     *   tags={"Users"},
     *   @OA\Response(response="201",description="User assign car",),
     *   @OA\Parameter(name="user_id",description="User id",in="path",required=true,@OA\Schema(type="string")),
     *   @OA\Parameter(name="car_id",description="Car id",in="path",required=true,@OA\Schema(type="string")),
     * ),
     */
    public function assignCar(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'car_id' => 'required|integer',
        ]);

        $user = User::attachCar($request->user_id,$request->car_id);
        return response()->json($user, 201);
    }

    /**
     * @OA\Post(
     *   path="/api/users",
     *   tags={"Users"},
     *   @OA\Response(response="201",description="Store user",),
     *   @OA\Parameter(name="title",description="User name",in="path",required=true,@OA\Schema(type="string")),
     * ),
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json($user, 201);
    }

    /**
     * @OA\Get(
     *   path="/api/users/{id}",
     *   tags={"Users"},
     *   @OA\Response(response="200",description="user id",),
     * ),
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return $user;
    }

    /**
     * @OA\Put(
     *   path="/api/users",
     *   tags={"Users"},
     *   @OA\Response(response="200",description="Update user",),
     *   @OA\Parameter(name="title",description="user name",in="path",required=true,@OA\Schema(type="string")),
     * ),
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
        ]);
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }

    /**
     * @OA\Delete(
     *   path="/api/users/{id}",
     *   tags={"Users"},
     *   @OA\Response(response="204",description="Delete user",),
     *   @OA\Parameter(name="id",description="user id",in="path",required=true,@OA\Schema(type="integer")),
     * ),
     */
    public function destroy($id)
    {
        if(User::find($id)->car()->exists()){
            return response()->json(['message' => 'user cannot be deleted, has car'],404);
        }
        User::destroy($id);

        return response()->json(null, 204);
    }
}

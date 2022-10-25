<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function car()
    {
        return $this->hasOne(Car::class,'car_id','id');
    }

    /**
     * @param $user_id
     * @param $car_id
     * @return false
     */
    public static function attachCar($user_id, $car_id)
    {

        $user = self::find($user_id);

        if(empty($user)){
          return false;
        }

        /**
         * Добавляет в историю.
         */

        DB::table('user_cars_history')->insert(['user_id' => $user_id, 'car_id' => $car_id]);

        $user->car_id = $car_id;

        $user->save();

        return true;
    }
}

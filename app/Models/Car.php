<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function user()
    {
        return $this->belongsTo(User::class,'car_id','id');
    }

    public static function addCar(string $title): self
    {
        return static::create([
            'title' => $title,
        ]);
    }

    public function editCar()
    {
        
    }
}

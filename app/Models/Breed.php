<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    protected $fillable = ['name', 'species', 'description'];

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
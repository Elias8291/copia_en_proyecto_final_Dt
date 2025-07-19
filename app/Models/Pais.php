<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';
    protected $fillable = ['nombre']; 

    public function estados()
    {
        return $this->hasMany(Estado::class, 'pais_id');
    }
}
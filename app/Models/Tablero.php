<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tablero extends Model
{
    use HasFactory;

    protected $with = ['partida'];

    public function partida() {
        return $this->belongsTo(Partida::class);
    }
}

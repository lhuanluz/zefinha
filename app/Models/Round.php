<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'round_number',
        'start_date',
        'end_date',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    // Aqui você pode adicionar métodos adicionais conforme necessário
}

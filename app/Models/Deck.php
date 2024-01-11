<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'commander_name',
        'user_id',
        'cards',
        'urlLigamagic',
        'valorMin',
    ];

    // Relação com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'deck_tournament_user', 'deck_id', 'tournament_id')
            ->withPivot('user_id')
            ->withTimestamps();
    }
}

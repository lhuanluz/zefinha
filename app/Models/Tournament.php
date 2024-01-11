<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rounds',
        'start_date',
        'status',
        'max_decks_per_user',
        'user_id',
    ];

    protected $dates = [
        'start_date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'deck_tournament_user', 'tournament_id', 'user_id')
            ->withTimestamps();
    }

    public function decks()
    {
        return $this->belongsToMany(Deck::class, 'deck_tournament_user', 'tournament_id', 'deck_id')
            ->withPivot('user_id')
            ->withTimestamps();
    }

    public function isOwner($user)
    {
        return $this->user_id === $user->id;
    }

    public function hasUserReachedMaxDecks($user)
    {
        return $this->decks()
                ->where('deck_tournament_user.user_id', $user->id) // Especifique a tabela usando o alias 'deck_tournament_user'
                ->count() >= $this->max_decks_per_user;
    }

}

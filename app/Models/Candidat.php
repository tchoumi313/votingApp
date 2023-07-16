<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;
    protected $guarded=[

    ];
    protected $fillable=[
        'nom' ,
        'prenom' ,
        'DateNaissance',
        'matricule' ,
        'faculte' ,
        'photo',
        'filiere',
        'niveau',
        'motivation',
        'cptVote',
    ];

    public function electeur(){
        return $this->hasMany(Electeur::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}

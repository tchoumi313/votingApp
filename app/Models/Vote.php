<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidat_id',
        'voter_id',
    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }
}

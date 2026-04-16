<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $fillable = [
        'type_coffret',
        'distributeur',
        'contact',
        'installateur',
        'contact_installateur',
        'affaire',
        'email',
        'telephone',
        'donnees',
        'observations',
        'statut',
    ];

    protected $casts = [
        'donnees' => 'array',
    ];
}
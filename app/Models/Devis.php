<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $fillable = [
        'type_coffret',
        'reference',
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
        'pdf_path',
    ];

    protected $casts = [
        'donnees' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Devis $devis) {
            $annee   = now()->year;
            $dernier = static::whereYear('created_at', $annee)->count() + 1;
            $devis->reference = sprintf('BALS-%d-%05d', $annee, $dernier);
        });
    }
}
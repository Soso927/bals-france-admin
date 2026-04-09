<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Eloquent représentant une région commerciale.
 *
 * Une région possède un nom (ex: "Île-de-France") et un label de zone
 * (ex: "ÎLE-DE-FRANCE") affiché sur la carte. Elle peut avoir plusieurs
 * agents commerciaux rattachés.
 *
 * @property int    $id
 * @property string $nom
 * @property string $zone
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Region extends Model
{
    /**
     * Les colonnes que l'on autorise à remplir en masse.
     * C'est une protection contre les attaques de type "mass assignment" :
     * sans ce tableau, un utilisateur malveillant pourrait envoyer des données
     * pour remplir des colonnes qu'il ne devrait pas pouvoir modifier.
     */
    protected $fillable = ['nom', 'zone'];

    /**
     * Relation "une région a plusieurs agents" (One-to-Many).
     *
     * Grâce à cette méthode, on peut écrire $region->agents pour
     * récupérer tous les agents d'une région, sans écrire de SQL.
     * Eloquent comprend automatiquement que la clé étrangère est "region_id"
     * dans la table agents.
     */
    public function agents(): HasMany
    {
        // orderBy('id') garantit que les agents sont toujours retournés
        // dans l'ordre défini par l'administrateur.
        return $this->hasMany(Agent::class)->orderBy('id');
    }
}
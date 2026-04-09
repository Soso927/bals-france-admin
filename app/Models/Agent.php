<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Eloquent représentant un agent commercial.
 *
 * Un agent appartient à une région (relation inverse de HasMany).
 * Il contient toutes les informations de contact affichées sur la carte
 * interactive et dans le tableau de bord admin.
 *
 * @property int         $id
 * @property int         $region_id
 * @property string|null $agence
 * @property string      $nom
 * @property string|null $departement
 * @property string|null $tel
 * @property string|null $tel_raw
 * @property string      $email
 */
class Agent extends Model
{
    /**
     * Colonnes autorisées à être remplies en masse (via create() ou fill()).
     * On liste explicitement chaque colonne modifiable pour la sécurité.
     */
    protected $fillable = [
        'region_id',
        'agence',
        'nom',
        'departement',
        'tel',
        'tel_raw',
        'email',

    ];

    /**
     * Relation inverse : un agent "appartient à" une région (BelongsTo).
     *
     * Grâce à cette méthode, on peut écrire $agent->region pour
     * récupérer la région d'un agent directement, comme un attribut.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
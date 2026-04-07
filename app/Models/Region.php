<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'zone',
		'color',
	];

	public function agents(): HasMany
	{
		return $this->hasMany(Agent::class)->orderBy('order');
	}

    /**
     * cette méthode formate la région et ses agents comme l'objet JavaScript CONTACTS l'attendait dans l'ancienne version.
     * ça facilite la transition depuis localStorage vers l'API.
     */

    public function toMapFormat():array
    {
        return [
            'zone' => $this->zone,
            'color' => $this->color,
            'agents'=> $this->agents->map(fn($agent) => [
                'agence' => $agent->agence,
                'nom'=> $agent->nom,
                'departement'=> $agent->departement,
                'tel'=> $agent->tel,
                'telRaw' => $agent->tel_raw,
                'email' => $agent->email,
            ])->toArray(),
        ];
    }
}

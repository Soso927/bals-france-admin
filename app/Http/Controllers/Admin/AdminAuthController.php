<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
	// Affiche la page de connexion 
	// si l'admin est déja connecté, on le redirige directement au dashboard 
	public function showLogin()
	{
		if (auth()->check() && auth()->user()->is_admin()) {
			return redirect()->route('admin.dashboard');
		}
		return view ('admin.login');
	}
	// Traite le formulaire de connexion
	public function login (Request $request)
	{
		// Validation des champs du formulaire 
		$credentials = $request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		// On tente la connexion avec les identifiants fournis
		if(Auth::attempt($credentials)){

		// Connexion réussie - mais on vérifie quand même que c'est bien un admin
		// Un utilisateur normal ne doit pas pouvoir accéder au back-office
		if(!auth()->user()->is_admin){
			Auth::logout();
			return back()->withErrors([
				'email' => 'Ce compte n\'a pas les droits administrateur.',
			]);
		
		} 

		// Régènère la session pour éviter les attaques par fixation de session
		$request->session()->regenerate();

		return redirect()->route('admin.dashboard');
	}

	// Connexion échouée - on renvoie une erreur générique 
	// (on ne précise pas si c'est l'email ou le mot de passe qui est faux,
	// pour ne pas donner d'indice à quelqu'un qui essaierait de deviner)

	return back()->withErrors([
		'email' => 'Identifiants incorrects',
	]);
}
  // Déconnecte l'admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
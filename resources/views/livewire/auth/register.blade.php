<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Gérer une demande d'inscription.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        // Après l'inscription, on redirige selon le rôle de l'utilisateur.
        // Un admin nouvellement créé va vers le dashboard d'administration.
        // Un utilisateur ordinaire va vers la page d'accueil.
        if (auth()->user()->is_admin) {
            $this->redirect(route('admin.dashboard'), navigate: true);
        } else {
            $this->redirect(route('home'), navigate: true);
        }
    }
}; ?>

<div class="flex flex-col gap-6 items-center !text-black">
    <img src="{{ asset('images/logo-Bals.png') }}" alt="Logo Bals" class="h-16 mb-2">

    <x-auth-header title="Créer un compte Administrateur"
        description="Saisissez vos coordonnées ci-dessous pour créer votre compte" class="!text-black" />

    <x-auth-session-status class="text-center text-[#151515]" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6 w-full max-w-md">
        <div class="grid gap-2">
            <flux:input wire:model="name" id="name" label="Nom complet" type="text" name="name" required
                autofocus autocomplete="name" placeholder="Ex: Jean Dupont" class="!text-black" />
        </div>

        <div class="grid gap-2">
            <flux:input wire:model="email" id="email" label="Adresse e-mail" type="email" name="email" required
                autocomplete="email" placeholder="nom@exemple.com" class="!text-black" />
        </div>

        <div class="grid gap-2">
            <flux:input wire:model="password" id="password" label="Mot de passe" type="password" name="password"
                required autocomplete="new-password" placeholder="Choisissez un mot de passe" class="!text-black" />
        </div>

        <div class="grid gap-2">
            <flux:input wire:model="password_confirmation" id="password_confirmation" label="Confirmer le mot de passe"
                type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="Répétez le mot de passe" class="!text-black" />
        </div>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                Créer le compte
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm">
        <span class="text-[#151515]">Déjà un compte ?</span>
        <x-text-link href="{{ route('login') }}" wire:navigate class="!text-[#151515] font-semibold underline">
            Se connecter
        </x-text-link>
    </div>
</div>

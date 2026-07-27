Bonjour {{ $staffName }},

Un compte {{ __('staff.role_' . strtolower($role)) }} a été créé pour vous dans l'hôtel {{ $hotelName }}.

Vos identifiants de connexion :
- Email : {{ $email }}
- Mot de passe : {{ $password }}
- Rôle : {{ __('staff.role_' . strtolower($role)) }}

Connectez-vous ici : {{ $loginUrl }}

Pour votre sécurité, pensez à modifier votre mot de passe après votre première connexion.

© {{ date('Y') }} {{ config('app.name', 'checkinHub') }}

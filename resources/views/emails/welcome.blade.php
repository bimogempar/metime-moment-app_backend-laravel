link {{ $type_set_password }} password for account {{ $user->email }}

<a href="{{ env('LINK_RESET_PASSWORD_TOKEN_IN_SPA') }}/set-init-pass/{{ $token_initial_password }}">{{ env('LINK_RESET_PASSWORD_TOKEN_IN_SPA') }}api/set-pass/{{ $token_initial_password }}/a>

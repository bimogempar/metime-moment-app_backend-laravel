this is for new project notifications
this name : {{ $user->name }}
this client : {{ $newproject->client }}
for url project : <a
    href="{{ env('FRONTEND_NEXTJS') }}projects/{{ $newproject->slug }}">{{ env('FRONTEND_NEXTJS') }}projects/{{ $newproject->slug }}</a>

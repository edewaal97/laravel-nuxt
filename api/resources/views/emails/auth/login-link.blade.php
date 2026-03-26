<x-mail::message>
# Link om in te loggen

Klik op de knop om in te loggen:

<x-mail::button :url="$url">Login</x-mail::button>

Of kopieer en plak de volgende link in uw browser:
<div style="word-break: break-all;">{!! $url !!}</div>

*Deze mail is na verzenden een uur geldig. Vraag een nieuwe mail aan via de <a href="{{ route("login") }}">loginpagina</a>*

</x-mail::message>

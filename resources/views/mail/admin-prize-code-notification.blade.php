@component('mail::message')
# ¡Un jugador ha ganado un código en el juego!

Se ha entregado un nuevo código de premio.

**Detalles:**
* **Código de Premio:** `{{ $code }}`
* **ID de Sesión del Jugador:** `{{ $sessionId }}`

Por favor, revisa tus registros si necesitas más detalles.

@component('mail::button', ['url' => route('admin.prize-codes.index')])
Ir al Sitio Web
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
<?php

/*
|--------------------------------------------------------------------------
| Mercado Pago — Invitatorio
|--------------------------------------------------------------------------
|
| Configuración de la integración con Mercado Pago Checkout Pro.
| Los valores se leen del .env; este archivo solo define defaults.
|
| ⚠️ Variables de entorno necesarias (definidas en .env):
|   MP_PUBLIC_KEY      → Public key de la app (APP_USR-... o TEST-...)
|   MP_TOKEN           → Access token privado (server-side, NUNCA al frontend)
|   MP_CLIENT_ID       → Para OAuth Connect (opcional, futuro)
|   MP_CLIENT_SECRET   → Para OAuth Connect (opcional, futuro)
|
| 📌 Los nombres de las variables siguen el estándar que ya cargaste
|    en el .env. Si prefieres renombrarlos a MERCADO_PAGO_*, ajusta
|    también este archivo y los docs de despliegue.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Credenciales
    |--------------------------------------------------------------------------
    | Access token de la app de Mercado Pago. Es el mismo para Checkout Pro
    | y para la API REST de pagos / preferencias.
    */
    'access_token' => env('MP_TOKEN'),

    /*
    | Public key que se usa en el frontend (MercadoPago.js / Checkout Bricks).
    | No es sensible como el access token, pero lo tratamos igual de cuidado.
    */
    'public_key' => env('MP_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | OAuth (Mercado Pago Connect)
    |--------------------------------------------------------------------------
    | Habilitan el flujo "Conectar con Mercado Pago" donde un vendedor
    | autoriza su cuenta. Para v1 NO los usamos (recibimos pagos con la
    | cuenta propia de Invitatorio), pero los dejamos listos para cuando
    | quieras permitir que clientes con cuenta MP reciban directo.
    */
    'oauth' => [
        'client_id'     => env('MP_CLIENT_ID'),
        'client_secret' => env('MP_CLIENT_SECRET'),
        'redirect_uri'  => env('MP_OAUTH_REDIRECT_URI'),
    ],

    /*
    |--------------------------------------------------------------------------
    | URLs de retorno del Checkout Pro
    |--------------------------------------------------------------------------
    | Mercado Pago redirige al usuario a estas URLs cuando termina el pago.
    | Usamos config('app.url') para que cambien solas entre local y prod,
    | pero se pueden fijar con variables de entorno si lo prefieres.
    */
    'back_urls' => [
        'success' => env('MP_BACK_URLS_SUCCESS',  fn () => rtrim(config('app.url'), '/') . '/checkout/success'),
        'pending' => env('MP_BACK_URLS_PENDING',  fn () => rtrim(config('app.url'), '/') . '/checkout/pending'),
        'failure' => env('MP_BACK_URLS_FAILURE',  fn () => rtrim(config('app.url'), '/') . '/checkout/failure'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook IPN
    |--------------------------------------------------------------------------
    | URL que MP llama en segundo plano para notificar cambios de estado.
    | NO es donde redirige al usuario; es la fuente real de verdad.
    | Se configura también en el panel de MP → "URL de notificaciones".
    */
    'webhook_url' => env('MP_WEBHOOK_URL', fn () => rtrim(config('app.url'), '/') . '/api/mercadopago/webhook'),

    /*
    |--------------------------------------------------------------------------
    | Moneda y zona
    |--------------------------------------------------------------------------
    | MXN porque la landing está en pesos mexicanos. Si en el futuro se
    | venden invitaciones en USD, este valor se puede pasar al preference
    | a nivel de item.
    */
    'currency' => env('MP_CURRENCY', 'MXN'),

    /*
    |--------------------------------------------------------------------------
    | Expiración de la preference
    |--------------------------------------------------------------------------
    | Tiempo (en horas) que la preference queda activa. Pasado eso, MP
    | rechaza la preferencia con error. 24h es más que suficiente.
    */
    'expires_in_hours' => (int) env('MP_EXPIRES_IN_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | Modo "sandbox" / "production"
    |--------------------------------------------------------------------------
    | Lo determina automáticamente el prefijo del access token:
    |   - "TEST-..."       → sandbox (tarjetas de prueba, no se cobra)
    |   - "APP_USR-..."    → producción
    */
    'is_sandbox' => str_starts_with((string) env('MP_TOKEN', ''), 'TEST-'),

    /*
    |--------------------------------------------------------------------------
    | Notificación por email al admin
    |--------------------------------------------------------------------------
    | Email al que se envía aviso cuando entra una orden pagada.
    | Si está vacío, no se envía nada.
    */
    'admin_email' => env('MP_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS')),

];

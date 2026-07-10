@php
    $siteName = 'Invitatorio';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Políticas de privacidad | {{ $siteName }}</title>
    <meta name="description" content="Cómo usamos los datos que nos compartes al cotizar o crear tu invitación digital en {{ $siteName }}.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/politicas-de-privacidad') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#FFFDF8] text-[#18111F] antialiased font-sans">
    <div class="mx-auto max-w-3xl px-5 sm:px-8 pt-14 pb-24">
        <a href="{{ url('/') }}" class="text-[13px] font-semibold text-[#EB7512] hover:text-[#F45A00]">
            ← Volver al inicio
        </a>

        <h1 class="mt-6 text-3xl sm:text-4xl font-display font-extrabold leading-tight text-[#2B143F]">
            Políticas de privacidad
        </h1>
        <p class="mt-2 text-sm text-[#5F5A66]">Última actualización: {{ date('d/m/Y') }}</p>

        <div class="prose prose-neutral mt-8 max-w-none text-[15px] sm:text-base leading-relaxed text-[#18111F] space-y-6">
            <section>
                <h2 class="font-display font-bold text-xl text-[#2B143F]">1. Datos que recibimos</h2>
                <p>Cuando nos escribes por WhatsApp o llenas el formulario de RSVP, recibimos: tu nombre, teléfono, mensaje opcional, y los datos del evento (nombres, fecha, lugar, mensajes, fotos).</p>
            </section>

            <section>
                <h2 class="font-display font-bold text-xl text-[#2B143F]">2. Para qué los usamos</h2>
                <p>Solo para diseñar y entregar tu invitación digital, y para enviarte mensajes relacionados con tu pedido. No vendemos ni compartimos tu información con terceros.</p>
            </section>

            <section>
                <h2 class="font-display font-bold text-xl text-[#2B143F]">3. Almacenamiento</h2>
                <p>Tus datos viven en nuestro servidor. Si quieres que los borremos, escríbenos por WhatsApp y los eliminamos en un máximo de 5 días hábiles.</p>
            </section>

            <section>
                <h2 class="font-display font-bold text-xl text-[#2B143F]">4. Tus derechos</h2>
                <p>Puedes pedirnos en cualquier momento que eliminemos tus datos o que te digamos qué tenemos guardado. Tu derecho a ser olvidado está garantizado.</p>
            </section>

            <section>
                <h2 class="font-display font-bold text-xl text-[#2B143F]">5. Contacto</h2>
                <p>Para cualquier duda, escríbenos por WhatsApp al botón de la página principal o al correo <a href="mailto:hola@invitatorio.com" class="text-[#EB7512] font-semibold hover:underline">hola@invitatorio.com</a>.</p>
            </section>
        </div>

        <p class="mt-12 text-xs text-[#5F5A66]">
            © {{ date('Y') }} {{ $siteName }}. Página legal generada para cumplimiento de privacidad en México.
        </p>
    </div>
</body>
</html>

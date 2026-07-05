# Invitaciones Digitales

Sitio web para ofrecer, vender y administrar invitaciones digitales personalizadas para eventos como bodas, XV años, cumpleaños, bautizos, aniversarios y reuniones especiales.

El objetivo del proyecto es crear una plataforma sencilla, elegante y funcional donde los clientes puedan conocer los paquetes disponibles, solicitar su invitación digital y compartirla fácilmente con sus invitados por WhatsApp, redes sociales o enlace directo.

## Objetivo del proyecto

Crear una landing page profesional para vender invitaciones digitales y, posteriormente, desarrollar un sistema administrativo en Laravel para gestionar clientes, eventos, plantillas, pagos y confirmaciones de asistencia.

Este proyecto busca ser una solución rentable, rápida de entregar y fácil de administrar, enfocada en clientes que necesitan una invitación moderna, bonita y práctica sin depender de invitaciones impresas.

## Tecnologías principales

* Laravel
* Blade
* Tailwind CSS
* Alpine.js
* MySQL
* Livewire o Filament para el panel administrativo
* Mercado Pago para pagos en línea
* WhatsApp como canal principal de contacto

## Público objetivo

El sitio está dirigido a personas que necesitan una invitación digital para:

* Bodas
* XV años
* Cumpleaños
* Bautizos
* Baby showers
* Aniversarios
* Eventos familiares
* Eventos religiosos
* Eventos empresariales sencillos

## Paquetes comerciales

### Invitación Esencial — $600 MXN

Paquete ideal para eventos sencillos que necesitan una invitación bonita, clara y fácil de compartir.

Incluye:

* Diseño adaptable a celular
* Nombres del evento
* Fecha y hora
* Ubicación con botón a Google Maps
* Botón de WhatsApp
* Música de fondo
* Cuenta regresiva
* Dress code
* Mesa de regalos
* Frase o mensaje especial
* Enlace personalizado

### Invitación Plus — $900 MXN

Paquete ideal para quienes quieren una invitación más visual, emotiva y completa.

Incluye todo lo del paquete Esencial, más:

* Galería de fotos
* Itinerario del evento
* Botón para agregar al calendario
* Secciones adicionales con más detalle visual
* Diseño con mayor personalización sobre plantilla

### Invitación Premium — $1,300 MXN

Paquete pensado para bodas, XV años y eventos más formales que necesitan confirmación de asistencia.

Incluye todo lo del paquete Plus, más:

* Confirmación de asistencia
* Formulario RSVP
* Lista básica de invitados confirmados
* Sección de familia, padres o padrinos
* Sección de recomendaciones
* Diseño más elegante y trabajado

### Invitación VIP Personalizada — desde $1,800 MXN

Paquete para clientes que buscan una invitación más única, elaborada y personalizada.

Incluye todo lo del paquete Premium, más:

* Diseño personalizado según temática del evento
* Animaciones especiales
* Galería extendida
* Secciones especiales
* Cambios adicionales
* Acompañamiento más directo durante el proceso

## Funciones principales de la landing page

La primera etapa del proyecto será una landing page enfocada en conversión.

La landing deberá incluir:

* Hero principal con mensaje claro
* Botón directo a WhatsApp
* Sección de beneficios
* Ejemplos de invitaciones digitales
* Sección de paquetes
* Proceso de contratación
* Preguntas frecuentes
* Testimonios o casos de ejemplo
* Llamadas a la acción
* Footer con datos de contacto

## Funciones futuras del sistema

En una segunda etapa, el proyecto podrá evolucionar a una plataforma completa con panel administrativo.

Funciones futuras:

* Registro de clientes
* Gestión de eventos
* Gestión de invitaciones digitales
* Catálogo de plantillas
* Personalización de colores, textos, música e imágenes
* Subida de fotografías
* Confirmación de asistencia
* Control de invitados
* Integración con Mercado Pago
* Estados de pago
* Enlaces personalizados para cada invitación
* Panel para administrar paquetes
* Panel para revisar solicitudes recibidas
* Estadísticas básicas de visitas y confirmaciones

## Estructura sugerida del sitio público

### Inicio

Página principal enfocada en presentar el servicio y convertir visitantes en prospectos.

Secciones sugeridas:

1. Hero principal
2. Beneficios de una invitación digital
3. Tipos de eventos
4. Ejemplos de diseños
5. Paquetes
6. Cómo funciona
7. Preguntas frecuentes
8. Contacto por WhatsApp

### Catálogo de diseños

Sección para mostrar ejemplos de invitaciones disponibles.

Cada diseño puede tener:

* Nombre de la plantilla
* Tipo de evento recomendado
* Vista previa
* Botón para solicitar ese diseño

### Paquetes

Sección comparativa con los planes disponibles:

* Esencial
* Plus
* Premium
* VIP Personalizada

### Contacto

Formulario o botón directo a WhatsApp para solicitar información.

Datos que se pueden pedir:

* Nombre del cliente
* Tipo de evento
* Fecha del evento
* Paquete de interés
* Mensaje adicional

## Estructura sugerida de rutas

```bash
/                           Página principal
/paquetes                   Paquetes disponibles
/disenos                    Catálogo de diseños
/disenos/{slug}             Vista previa de una plantilla
/contacto                   Página de contacto
/invitacion/{slug}          Invitación pública del cliente
/admin                      Panel administrativo
```

## Modelo inicial de datos

### Tabla: events

Información principal del evento.

Campos sugeridos:

* id
* client_name
* event_type
* event_title
* slug
* event_date
* event_time
* location_name
* location_address
* google_maps_url
* dress_code
* gift_table_url
* whatsapp_number
* music_url
* main_message
* package_type
* status
* created_at
* updated_at

### Tabla: guests

Invitados y confirmaciones.

Campos sugeridos:

* id
* event_id
* guest_name
* phone
* number_of_guests
* attendance_status
* message
* created_at
* updated_at

### Tabla: templates

Plantillas disponibles.

Campos sugeridos:

* id
* name
* slug
* event_type
* preview_image
* description
* is_active
* created_at
* updated_at

### Tabla: gallery_images

Fotografías para invitaciones con galería.

Campos sugeridos:

* id
* event_id
* image_path
* order
* created_at
* updated_at

## Estados sugeridos de una invitación

* Pendiente
* En diseño
* En revisión
* Aprobada
* Publicada
* Finalizada
* Cancelada

## Flujo básico de venta

1. El cliente entra a la landing.
2. Revisa los beneficios y paquetes.
3. Elige un paquete.
4. Contacta por WhatsApp.
5. Envía los datos de su evento.
6. Se crea la invitación digital.
7. El cliente revisa y aprueba.
8. Se publica la invitación con enlace personalizado.
9. El cliente comparte el enlace con sus invitados.

## Diferenciadores del servicio

* Invitaciones modernas y fáciles de compartir
* Diseños adaptados a celular
* Entrega rápida
* Ubicación directa con Google Maps
* Música de fondo
* Cuenta regresiva
* Dress code
* Mesa de regalos
* Confirmación de asistencia en paquetes avanzados
* Atención directa por WhatsApp

## Instalación del proyecto

Clonar el repositorio:

```bash
git clone https://github.com/usuario/invitaciones-digitales.git
```

Entrar al proyecto:

```bash
cd invitaciones-digitales
```

Instalar dependencias de PHP:

```bash
composer install
```

Instalar dependencias de Node:

```bash
npm install
```

Copiar archivo de entorno:

```bash
cp .env.example .env
```

Generar key de Laravel:

```bash
php artisan key:generate
```

Configurar base de datos en el archivo `.env`.

Ejecutar migraciones:

```bash
php artisan migrate
```

Levantar servidor local:

```bash
php artisan serve
```

Compilar assets:

```bash
npm run dev
```

## Comandos útiles

Ejecutar servidor local:

```bash
php artisan serve
```

Compilar assets en desarrollo:

```bash
npm run dev
```

Compilar assets para producción:

```bash
npm run build
```

Ejecutar migraciones:

```bash
php artisan migrate
```

Limpiar caché:

```bash
php artisan optimize:clear
```

## Prioridad inicial del desarrollo

La primera versión del proyecto debe enfocarse en vender, no en construir demasiadas funciones.

Prioridad del MVP:

1. Landing page profesional
2. Sección de paquetes
3. Botón de contacto por WhatsApp
4. Catálogo básico de diseños
5. Página pública de muestra de invitación
6. Formulario básico de solicitud

Después del MVP se puede avanzar al panel administrativo, pagos en línea y gestión completa de invitaciones.

## Estado del proyecto

Proyecto en etapa inicial.

Primera fase:

* Definir identidad visual
* Crear landing page
* Definir paquetes
* Crear primeras plantillas de invitación
* Preparar estructura base en Laravel

## Licencia

Proyecto privado para uso comercial.

<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(
            fn (Request $request) => $request->user()?->isAdmin() ? '/admin' : '/panel/invitaciones',
        );
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Diario: marca invitaciones publicadas cuya fecha_caducidad ya pasó.
        $schedule->command('invitaciones:marcar-vencidas')
            ->dailyAt('03:00')
            ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

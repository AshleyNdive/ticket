<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use App\Filament\Resources\TicketResource;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\TicketsKanbanBoard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])


            ->renderHook(
                'panels::user-menu.before',
                function () {

                    $role = Auth::user()?->getRoleNames()->first() ?? 'N/A';


                    if ($role === 'N/A') {
                        return '';
                    }

                    return '<div class="px-3 py-1 text-sm font-semibold rounded-lg bg-primary-500 text-white dark:bg-primary-600 mr-2">Role: ' . $role . '</div>';
                }
            )

            // Floating action button
           ->renderHook(
    'panels::footer',
    function () {

        $excludedRoutes = [
            'filament.admin.auth.login',
            'filament.admin.resources.tickets.*',
            //'filament.admin.pages.tickets-kanban-board',
        ];

        if (request()->routeIs($excludedRoutes)) {
            return '';
        }

        $createUrl = app(\App\Filament\Resources\TicketResource::class)::getUrl('create');

        return '
            <div class="w-full flex justify-end">
                <a href="' . $createUrl . '"
                    style="
                        position: fixed;
                        background-color: #f59e0b;
                        color: white;
                        bottom: 2rem;
                        right: 2rem;
                        z-index: 50;
                    "
                    class="
                        px-3 py-2 rounded-full
                        shadow-xl
                        hover:bg-amber-600 transition
                    "
                    title="Quick Create Ticket"
                >
                    <span class="fi-btn-icon h-5 w-5">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                    </span>
                </a>
            </div>
        ';
    }
)


            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                 FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])

            ;
    }
}

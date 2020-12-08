<?php

namespace Modules\Pages\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {
            // Separator: Module Management

            // Pages Dropdown
            $pages_menu = $menu->add('<i class="nav-icon fas fa-crown"></i> Pages', [
                'route' => 'backend.pages.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 81,
                'activematches' => 'admin/pages*',
                'permission'    => ['view_pages','edit_pages'],
            ]);
            $pages_menu->link->attr([
                'class' => 'nav-link',
            ]);

        })->sortby;
        return $next($request);
    }
}

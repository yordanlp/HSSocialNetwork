<?php

namespace App\View\Components;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\View\Component;

class Navigation extends Component
{

    public $nav_items = [];
    public function __construct()
    {
        $nav_user = $this->isAdminRoute() ? "admin" : "normal_user";
        $this->nav_items = $this->getUrlFromRoute(config('appsettings.navigation')[$nav_user]);
    }

    private function isAdminRoute()
    {
        $splitted_route = explode("/", Route::getFacadeRoot()->current()->uri());
        if (count($splitted_route) > 0 && strtolower($splitted_route[0]) === "admin")
            return true;
        return false;
    }


    private function getUrlFromRoute($nav_items)
    {
        return collect($nav_items)->map(function ($item) {
            $url = '/';
            switch ($item['title']) {
                case 'Profile':
                    $url = route($item['route'], auth()->user()->id);
                    break;
                default:
                    $url = route($item['route']);;
            }
            $item['url'] = $url;
            return $item;
        });
    }

    public function is_item_active($item)
    {
        return URL::current() === $item['url'];
    }

    public function render()
    {
        return view('components.navigation', ["nav_items" => $this->nav_items]);
    }
}

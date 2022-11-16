<?php

namespace App\View\Components;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\Component;

class Navigation extends Component
{

    public $nav_items = [];
    public function __construct()
    {
        $this->nav_items = $this->getUrlFromRoute(config('appsettings.navigation'));
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

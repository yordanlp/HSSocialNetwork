<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class UserList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $users = [];
    public function __construct($users)
    {
        $this->users = $users;
    }

    public function isAdminRoute()
    {
        $splitted_route = explode("/", Route::getFacadeRoot()->current()->uri());
        if (count($splitted_route) > 0 && strtolower($splitted_route[0]) === "admin")
            return true;
        return false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user-list');
    }
}

<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;

class UserCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public User $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user-card');
    }
}

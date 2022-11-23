<?php

namespace App\View\Components;

use App\Models\Post as ModelsPost;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Post extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public ModelsPost $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function getIfItsReply()
    {
        if ($this->post->getParentPostUserName() == null)
            return "";
        return $this->post->getParentPostUserName();
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
        return view('components.post');
    }
}

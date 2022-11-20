<?php

namespace App\View\Components;

use App\Models\Post as ModelsPost;
use Illuminate\View\Component;

use function PHPUnit\Framework\returnSelf;

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
        return "Replying to " . $this->post->getParentPostUserName();
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

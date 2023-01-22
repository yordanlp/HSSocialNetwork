<?php

namespace App\Http\Livewire;

use App\Models\Post as ModelsPost;
use App\Services\GoogleTranslate;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Post extends Component
{
    // protected $listeners = ['languageChanged' => 'refresh'];

    public ModelsPost $post;
    private GoogleTranslate $translatorService;

    public function mount(ModelsPost $post)
    {
        $this->post = $post;
        $this->showTranslation = false;
    }

    public function __construct(public bool $showTranslation = false, public ?string $translation = null)
    {
        $this->translatorService = new GoogleTranslate();
    }

    public function refresh()
    {
        $this->showTranslation = false;
        $this->translation = null;
    }

    public function getIfItsReply()
    {
        if ($this->post->getParentPostUserName() == null)
            return "";
        return $this->post->getParentPostUserName();
    }

    public function toogleTranslation()
    {
        $this->showTranslation = !$this->showTranslation;
        if ($this->showTranslation && $this->translation == null)
            $this->translation = $this->translatorService->translate($this->post->message, auth()->user()->profile->language);
    }

    public function getLanguageOfMessage()
    {
        return $this->translatorService->detectLanguage($this->post->message);
    }

    public function isAdminRoute()
    {
        $splitted_route = explode("/", Route::getFacadeRoot()->current()->uri());
        if (count($splitted_route) > 0 && strtolower($splitted_route[0]) === "admin")
            return true;
        return false;
    }

    public function render()
    {
        return view('livewire.post');
    }
}

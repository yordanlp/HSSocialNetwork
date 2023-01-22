<?php

namespace App\Http\Livewire;

use App\Services\GoogleTranslate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PostsWrapper extends Component
{
    protected $listeners = ['languageChanged' => '$refresh'];
    public string $section = '';
    private $posts;
    private GoogleTranslate $translator;
    public function __construct()
    {
        $this->translator = new GoogleTranslate();
    }

    public function mount(string $section)
    {
        $this->section = $section;
    }


    public function render()
    {
        $this->posts = Session::get($this->section);
        if ($this->posts != null && $this->posts->count() > 0) {
            $texts = $this->posts->map(fn ($post) => $post->message);
            $translations = $this->translator->translateMany($texts, auth()?->user()?->language ?? 'en');
            $this->posts = $this->posts = $this->posts->map(function ($post, $index) use ($translations) {
                $post['translatedText'] = $translations[$index]->translatedText;
                $post['detectedSourceLanguage'] = $translations[$index]->detectedSourceLanguage;
                return $post;
            });
        }

        return view('livewire.posts-wrapper', [
            'posts' => $this->posts
        ]);
    }
}

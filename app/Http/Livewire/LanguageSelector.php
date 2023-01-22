<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LanguageSelector extends Component
{
    public $languages = [
        'es' => 'Spanish',
        'en' => 'English',
        'ru' => 'Russian'

    ];

    public function changeLanguage($language)
    {
        ray($language);
        auth()->user()->profile->update(['language' => $language]);
        $this->emit('languageChanged', $language);
    }

    public function render()
    {
        return view('livewire.language-selector');
    }
}

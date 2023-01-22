<?php

namespace App\Services;

use App\Exceptions\NotSupportedLanguageException;
use App\Exceptions\NullTargetLanguageException;
use Illuminate\Support\Facades\Http;

class GoogleTranslate
{
    private string $api_key = "";
    private string $base_url = "https://translation.googleapis.com/language/translate/v2/";
    public $supported_languages = null;
    public function __construct()
    {
        $this->api_key = config('external-services.google_translate.api_key');
        $this->supported_languages = $this->getLanguages();
    }

    public function translate(string $text, ?string $target)
    {

        if (!$this->isSupportedLanguage($target))
            throw new NotSupportedLanguageException("Language '{$target}' is not supported for translation");

        if ($target == null)
            throw new NullTargetLanguageException("Target language must not be null");

        $data = $this->postRequest('', [
            'q' => $text,
            'target' => $target
        ]);

        return $data->data->translations[0]->translatedText;
    }

    public function translateMany($texts, ?string $target)
    {

        if (!$this->isSupportedLanguage($target))
            throw new NotSupportedLanguageException("Language '{$target}' is not supported for translation");

        if ($target == null)
            throw new NullTargetLanguageException("Target language must not be null");


        $data = $this->postRequest('', [
            'q' => $texts,
            'target' => $target
        ]);

        return $data->data->translations;
    }

    public function isSupportedLanguage(string $language)
    {
        return $this->supported_languages->contains($language);
    }

    public function getLanguages()
    {
        $data = $this->getRequest('languages');
        return collect($data->data->languages)->map(fn ($lang) => $lang->language);
    }

    public function detectLanguage($text)
    {
        $data = $this->postRequest('detect', [
            'q' => $text
        ]);
        return $data->data->detections[0][0]->language;
    }

    private function postRequest($endpoint, $params = [])
    {
        $response = Http::post($this->base_url . $endpoint . "?key=" . $this->api_key, $params);
        return json_decode($response->body());
    }

    private function getRequest($endpoint, $params = [])
    {
        $data = array_merge($params, ['key' => $this->api_key]);
        $response = Http::get($this->base_url . $endpoint . "?" . http_build_query($data));
        return json_decode($response->body());
    }
}

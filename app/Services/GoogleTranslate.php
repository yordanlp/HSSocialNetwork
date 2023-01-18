<?php

namespace App\Services;

use App\Exceptions\NotSupportedLanguageException;
use App\Exceptions\NullTargetLanguageException;
use Exception;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\CodeCoverage\Report\Xml\Source;

use function PHPUnit\Framework\returnSelf;

class GoogleTranslate
{
    private string $api_key = "";
    private string $base_url = "https://translation.googleapis.com/language/translate/v2/";
    private $supported_languages = null;
    public function __construct()
    {
        $this->api_key = config('external-services.google_translate.api_key');
        $this->supported_languages = $this->get_languages();
    }

    public function translate(string $text, string $target, string $source = null)
    {
        if ($source == null)
            $source = $this->detect_language($text);

        if ($source == $target)
            return $text;

        if (!$this->is_supported_language($source))
            throw new NotSupportedLanguageException("Language '{$source}' is not supported for translation");
        if (!$this->is_supported_language($target))
            throw new NotSupportedLanguageException("Language '{$target}' is not supported for translation");

        if ($target == null)
            throw new NullTargetLanguageException("Target language must not be null");

        $data = $this->get_request('', [
            'q' => $text,
            'target' => $target,
            'source' => $source
        ]);

        return $data->data->translations[0]->translatedText;
    }

    public function is_supported_language(string $language)
    {
        return $this->supported_languages->contains($language);
    }

    public function get_languages()
    {
        $data = $this->get_request('languages');
        return collect($data->data->languages)->map(fn ($lang) => $lang->language);
    }

    public function detect_language($text)
    {
        $data = $this->get_request('detect', [
            'q' => $text
        ]);
        return $data->data->detections[0][0]->language;
    }

    public function get_request($endpoint, $params = [])
    {
        $data = array_merge($params, ["key" => $this->api_key]);
        $response = Http::get($this->base_url . $endpoint, $data);
        return json_decode($response->body());
    }
}

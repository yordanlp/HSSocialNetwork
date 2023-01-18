<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\CodeCoverage\Report\Xml\Source;

class GoogleTranslate
{
    private string $api_key = "AIzaSyCW69t5qNg4118tniWUxCJe85yKVFISF4Q";
    private string $base_url = "https://translation.googleapis.com/language/translate/v2/";
    public function __construct()
    {
    }

    public function translate(string $text, string $target, string $source = null)
    {
        if ($source == null)
            $source = $this->detect_language($text);

        /*if ($target == null)
            throw new NullTargetLanguageException("Target language should not be null");*/

        $data = $this->get_request('', [
            'q' => $text,
            'target' => $target,
            'source' => $source
        ]);

        return $data->data->translations[0]->translatedText;
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

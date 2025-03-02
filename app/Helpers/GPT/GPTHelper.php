<?php

namespace App\Helpers\GPT;

use Illuminate\Support\Facades\Http;

/**
 * Class HtmlHelper.
 */
class GPTHelper
{
    /**
     * The input string
     *
     * @var \String
     */
    protected $_string;
    protected $_anal_threshold = 0.9;

    /**
     * HtmlHelper constructor.
     *
     * @param String|null $string
     */
    public function __construct(string $string = null)
    {
        $this->_string = $string;
    }

    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    public static function anonymize($string)
    {
        // return string for now
        if (env('PRESIDIO_ENABLED', false)) {

            $analURL = env('PRESIDIO_ANALYZER_ENDPOINT', null);
            $anonURL = env('PRESIDIO_ANONYMIZER_ENDPOINT', null);

            $analysis = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post($analURL, [
                "text" => $string,
                "language" => "en"
            ])->json();

            // dd($analysis);

            $redacted = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post($anonURL, [
                "text" => $string,
                "analyzer_results" => $analysis,
                "language" => "en",
                "anonymizers" => [
                    // How to handle the various identifies entities/types returned by the analyzer
                    // global identifiers
                    "PERSON" => [
                        "type" => "redact",
                    ],
                    "EMAIL_ADDRESS" => [
                        "type" => "redact",
                    ],
                    "IBAN_CODE" => [
                        "type" => "redact",
                    ],
                    "IP_ADDRESS" => [
                        "type" => "redact",
                    ],
                    "NRP" => [
                        "type" => "redact",
                    ],
                    "LOCATION" => [
                        "type" => "redact",
                    ],
                    "PHONE_NUMBER" => [
                        "type" => "redact",
                    ],
                    "MEDICAL_LICENSE" => [
                        "type" => "redact",
                    ],
                    // UK specific
                    "UK_NHS" => [
                        "type" => "redact",
                    ],
                    //AU specific
                    "AU_ABN" => [
                        "type" => "redact",
                    ],
                    "AU_ACN" => [
                        "type" => "redact",
                    ],
                    "AU_TFN" => [
                        "type" => "redact",
                    ],
                    "AU_MEDICARE" => [
                        "type" => "redact",
                    ],
                ],

            ])->json()['text'];
            return $redacted;
        } else {
            return $string;
        }
    }
}

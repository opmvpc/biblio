<?php

namespace App\Services;

use App\Services\ImporterBibtexService;

class ImporterDoiService
{
    private $doi;

    public function __construct(string $doi) {
        $this->doi = static::cleanDoi($doi);
    }

    public function save(): void
    {
        $bibtex = $this->getBibtex();
        (new ImporterBibtexService($bibtex, $this->doi))->save();
    }

    public function getBibtex(): string
    {

        $url = 'https://data.crosscite.org/application/x-bibtex/'. $this->doi;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);

        if ($response == 'The resource you are looking for doesn\'t exist.') {
            throw new \InvalidArgumentException("Error Processing Request, wrong Doi", 1);
        }

        return $this->sanatize($response);
    }

    public function doi(): string
    {
        return $this->doi;
    }

    public function sanatize(string $response): string
    {
        // $explodeResponse = preg_split('/\r\n|\r|\n/', $response, 2);
        // $explodeResponse[0] = "@article{\n";

        // return implode('', $explodeResponse);
        return $response;
    }

    private static function cleanDoi(string $doi): string
    {
        return preg_replace('/https:\/\/doi.org\//', '', $doi);
    }
}

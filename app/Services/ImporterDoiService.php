<?php

namespace App\Services;

use App\Services\ImporterBibtexService;

class ImporterDoiService
{
    private $doi;

    public function __construct(string $doi) {
        $this->doi = $doi;
    }

    public function save(): void
    {
        (new ImporterBibtexService($this->getBibtex()))->save();
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

        return $this->sanatize($response);
    }

    public function doi(): string
    {
        return $this->doi;
    }

    public function sanatize(string $response): string
    {
        $count = 1;
        return str_replace('https://doi.org/'. $this->doi() .',', '', $response, $count);
    }
}

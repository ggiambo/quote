<?php

require "vendor/autoload.php";
use PHPHtmlParser\Dom;

class FondInfo
{

    public $id;
    public $isin;
    public $name;
    public $url;
    public $quote;

    public function loadFundInfo($fondId)
    {
        $this->pupulateFundInfo($fondId);
        $this->populateFundQuote();
    }

    private function pupulateFundInfo($fondId)
    {
        $curlHandle = curl_init("https://www.finanzen.ch/ajax/SearchController_SuggestJson");
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("Content-Type: application/json",));
        curl_setopt($curlHandle, CURLOPT_HEADER, true);
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode(array("query" => $fondId)));
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_ENCODING, 'gzip');

        $response = curl_exec($curlHandle);

        $headerSize = curl_getinfo($curlHandle, CURLINFO_HEADER_SIZE);
        $body = substr($response, $headerSize);
        curl_close($curlHandle);

        $json = json_decode($body)->{"it"};
        $fonds = array_values(array_filter($json, function ($item) {
                return $item->{"n"} === "Fonds";
            })
        );

        $fond = $fonds[0]->{"il"}[0];
        $this->id = $fond->{"id"};
        $this->isin = $fond->{"isin"};
        $this->name = $fond->{"n"};
        $this->url = $fond->{"u"};
    }

    private function populateFundQuote()
    {
        $curlHandle = curl_init($this->url);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/142.0"));
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_ENCODING, 'gzip');

        $response = curl_exec($curlHandle);

        curl_close($curlHandle);

        $dom = new Dom;
        $dom->loadStr($response);

        $children = $dom->find(".pricebox > thead:nth-child(1) > tr:nth-child(1) > th:nth-child(1) > th:nth-child(1)");
        $this->quote = $children[0]->text;
    }

}
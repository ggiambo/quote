<?php

require_once "vendor/autoload.php";
use PHPHtmlParser\Dom;

class FondInfo
{
    private $fmt;
    public $isin;
    public $name;
    public $quote;

    function __construct()
    {
        $this->fmt = numfmt_create("de_CH", NumberFormatter::DECIMAL);
    }

    public function loadFundInfo($isin)
    {
        $fmt = numfmt_create("de_CH", NumberFormatter::DECIMAL);
        define("STDOUT", fopen("php://stdout", "wb"));

        $curlHandle = curl_init("https://www.finanzpartner.de/fi/$isin");

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/142.0",
        ]);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_ENCODING, "gzip");

        $response = curl_exec($curlHandle);

        curl_close($curlHandle);

        $dom = new Dom();
        $dom->loadStr($response);

        $this->isin = $isin;
        $this->name = $dom->find("h1")[0]->text;
        $this->quote = numfmt_parse($fmt, $dom->find("span.kurs,span.kurs-m,span.kurs-s")[0]->text);
    }
}

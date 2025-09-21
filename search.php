<?php
require "vendor/autoload.php";

use PHPHtmlParser\Dom;

function getFondUrl($fondId)
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
    return $fonds[0]->{"il"}[0]->{"u"};
}

function getFondQuote($fondUrl)
{
    $curlHandle = curl_init($fondUrl);
    curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/142.0"));
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlHandle, CURLOPT_ENCODING, 'gzip');

    $response = curl_exec($curlHandle);

    $headerSize = curl_getinfo($curlHandle, CURLINFO_HEADER_SIZE);
    $body = substr($response, $headerSize);
    curl_close($curlHandle);

    $dom = new Dom;
    $dom->loadStr($body);

    $price = $dom->find(".pricebox > thead:nth-child(1) > tr:nth-child(1) > th:nth-child(1) > th:nth-child(1)")[0];

    return $price->text;
}

$fondId = $_GET["id"];
$fondUrl = getFondUrl($fondId);
$price = getFondQuote($fondUrl);

header('Content-Type: application/json: charset=utf-8');
echo json_encode(array('price' => $price));

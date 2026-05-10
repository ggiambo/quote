<?php

require_once "FondInfo.php";

$fondId = $_GET["id"];
if ($fondId == "") {
    http_response_code(400);
    exit;
}

$fondRegex = "/\D{2}[a-zA-Z0-9]{9}\d/"; // https://en.wikipedia.org/wiki/International_Securities_Identification_Number#Description
if (!preg_match($fondRegex,$fondId)) {
    http_response_code(400);
    exit;
}

$fondInfo = new FondInfo();
$fondInfo->loadFundInfo($fondId);

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
echo json_encode($fondInfo);

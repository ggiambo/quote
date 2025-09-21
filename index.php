<?php

require "FondInfo.php";

$fondId = $_GET["id"];
$fondInfo = new FondInfo();
$fondInfo->loadFundInfo($fondId);

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
echo json_encode($fondInfo);

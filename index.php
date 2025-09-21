<?php

require "FondInfo.php";

$fondId = $_GET["id"];
$fondInfo = new FondInfo();
$fondInfo->loadFundInfo($fondId);

header("Content-Type: application/json; charset=utf-8");
echo json_encode($fondInfo);

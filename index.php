<?php

require "FondInfo.php";

$fondId = $_GET["id"];
$fondInfo = new FondInfo();
$fondInfo->loadFundInfo($fondId);

echo json_encode($fondInfo);

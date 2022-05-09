<?php

include_once("./database.php");
include_once("./lib.php");

header('Cache-Control: no-cache');
header("Content-Type: text/event-stream\n\n");

$iID = (int) file_get_contents("/tmp/last_link_id");
$oLast = null;

if ($iID) {
    $oLast = R::findOne(T_LINKS, "id > ? ORDER BY id DESC LIMIT 1", [$iID]);
} else {
    $oLast = R::findOne(T_LINKS, "ORDER BY id DESC LIMIT 1");
}

if ($oLast) {
    echo "event: notify_add\n";
    echo 'data: '.json_encode([ "name" => $oLast->name ]);
    echo "\n\n";

    file_put_contents("/tmp/last_link_id", $oLast->id);
}
<?php 

include_once("./database.php");
include_once("./lib.php");

$aRequest = $_REQUEST; // json_decode(file_get_contents("php://input"), true);
$sMethod = $_REQUEST['method'];

include_once("./models/tags.php");

include_once("./controllers/groups.php");
include_once("./controllers/categories.php");
include_once("./controllers/links.php");
include_once("./controllers/domains.php");
include_once("./controllers/notes.php");
include_once("./controllers/tags.php");
include_once("./controllers/telegram.php");

header("HTTP/1.1 404 Not Found"); 
die();

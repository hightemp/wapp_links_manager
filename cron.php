<?php

chdir("/var/www");

include_once("./database.php");
include_once("./lib.php");

include_once("./extract_domains.php");
include_once("./extract_titles.php");
include_once("./telegram_update.php");

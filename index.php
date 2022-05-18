<?php 

include_once("./database.php");

$sLayout = isset($_GET["layout"]) ? $_GET["layout"] : "index";

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>

    <link rel="shortcut icon" href="<?php echo $sBase ?>/static/app/favicon.png" type="image/png">

    <link rel="stylesheet" type="text/css" href="<?php echo $sB ?>/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $sB ?>/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $sBA ?>/styles_index.css">
    <script type="text/javascript" src="<?php echo $sB ?>/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $sB ?>/jquery.easyui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo $sBA ?>/easymde.min.css">
    <script type="text/javascript" src="<?php echo $sBA ?>/easymde.min.js"></script>
    <script type="text/javascript" src="<?php echo $sBA ?>/all.js"></script>
    <script type="text/javascript" src="<?php echo $sBA ?>/speadsheet.js"></script>

    <script type="text/javascript" src="<?php echo $sBA ?>/hotkeys.min.js"></script>

    <script type="text/javascript" src="<?php echo $sBA ?>/datagrid-filter.js"></script>
    <script type="text/javascript" src="<?php echo $sBA ?>/datagrid-cellediting.js"></script>

    <script src="https://cdn.jsdelivr.net/highlight.js/latest/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/highlight.js/latest/styles/github.min.css">
</head>
<body>
    <div id="main-panel">
        <?php if ($sLayout == "index"): ?>
            <?php include "includes/index_page.php" ?>
        <?php elseif ($sLayout == "add_link"): ?>
            <?php include "includes/add_link_page.php" ?>
        <?php endif ?>
    </div>
    <?php
    if (!is_writable("./db/dbfile.db")) {
        ?><div style="background: red; color: white; padding: 10px">./db/dbfile.db is not writable</div><?php
    }
    ?>
</body>
</html>
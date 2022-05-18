<?php

// Таблицы

if ($sMethod == 'list_tree_files') {
    if (isset($aRequest['group_id']) && $aRequest['group_id']>0) {
        $aFiles = (array) R::findAll(T_FILES, 'tgroups_id = ? AND tfiles_id IS NULL ORDER BY id DESC', [$aRequest['group_id']]);
    } else {
        $aFiles = (array) R::findAll(T_FILES, 'tfiles_id IS NULL ORDER BY id DESC');
    }

    foreach ($aFiles as $oFile) {
        $oFile->count = (int) $oFile->countOwn(T_LINKS);
    }

    die(json_encode(array_values($aFiles)));
}

if ($sMethod == 'list_files_paged') {
    $sFilterRules = " 1 = 1";
    if (isset($aRequest['filterRules'])) {
        $aRequest['filterRules'] = json_decode($aRequest['filterRules']);
        $sFilterRules = fnGenerateFilterRules($aRequest['filterRules']);
    }

    $sOffset = fnPagination($aRequest['page'], $aRequest['rows']);
    $aResult = [];

    if (isset($aRequest['group_id']) && $aRequest['group_id']>0) {
        $aResult['rows'] = (array) R::findAll(T_FILES, "{$sFilterRules} AND tgroups_id = ? ORDER BY id DESC {$sOffset}", [$aRequest['group_id']]);
        $aResult['total'] = R::count(T_FILES, "{$sFilterRules} AND tgroups_id = ?", [$aRequest['group_id']]);
    } else {
        $aResult['rows'] = (array) R::findAll(T_FILES, "{$sFilterRules} ORDER BY id DESC {$sOffset}", []);
        $aResult['total'] = R::count(T_FILES, "{$sFilterRules}");
    }

    foreach ($aResult['rows'] as $oFile) {
        $oFile->count = (int) $oFile->countShared(T_LINKS);
    }

    // // if (isset($aRequest['group_id']) && $aRequest['group_id']>0) {
    // //     $aFiles = R::findAll(T_FILES, 'tgroups_id = ? AND tfiles_id IS NULL ORDER BY id DESC', [$aRequest['group_id']]);
    // // } else {
    // //     $aFiles = R::findAll(T_FILES, 'tfiles_id IS NULL ORDER BY id DESC');
    // // }

    $aResult['rows']= array_values($aResult['rows']);

    die(json_encode($aResult));
}

if ($sMethod == 'list_files') {
    $aFiles = (array) R::findAll(T_FILES);

    foreach ($aFiles as $oFile) {
        $oFile->count = (int) $oFile->countOwn(T_LINKS);
    }

    die(json_encode(array_values($aFiles)));
}

if ($sMethod == 'get_file') {
    $aResponse = R::findOne(T_FILES, "id = ?", [$aRequest['id']]);
    die(json_encode($aResponse));
}

if ($sMethod == 'delete_file') {
    $oFile = R::findOne(T_FILES, "id = ?", [$aRequest['id']]);

    R::trashAll([$oFile]);

    die(json_encode([]));
}

if ($sMethod == 'update_file') {
    $oFile = R::findOne(T_FILES, "id = ?", [$aRequest['id']]);

    $oFile->updated_at = date("Y-m-d H:i:s");
    $oFile->description = $aRequest['description'];
    if ($aRequest['name']) {
        $oFile->name = $aRequest['name'];
    }
    if ($_FILES) {
        if (!$aRequest['name']) {
            $oFile->name = $_FILES['file']['name'];
        }
        preg_match("/(\w+)$/", $_FILES['file']['type'], $aM);
        $oFile->type = $_FILES['file']['type'];
        $oFile->filename = $oFile->timestamp.".".@$aM[1];

        $sFilePath = $sFIP."/".$oFile->filename;
        $sRelFilePath = $sIP."/".$oFile->filename;
        copy($_FILES['file']['tmp_name'], $sFilePath);
    }

    R::store($oFile);

    die(json_encode([
        "id" => $oFile->id, 
        "name" => $oFile->name
    ]));
}

if ($sMethod == 'create_file') {
    $oFile = R::dispense(T_FILES);

    $oFile->created_at = date("Y-m-d H:i:s");
    $oFile->updated_at = date("Y-m-d H:i:s");
    $oFile->timestamp = time();
    if ($aRequest['name']) {
        $oFile->name = $aRequest['name'];
    }

    if ($_FILES) {
        if (!$aRequest['name']) {
            $oFile->name = $_FILES['file']['name'];
        }
        preg_match("/(\w+)$/", $_FILES['file']['name'], $aM);
        $oFile->type = $_FILES['file']['type'];
        $oFile->filename = $oFile->timestamp.".".@$aM[1];

        $sFilePath = $sFIP."/".$oFile->filename;
        $sRelFilePath = $sIP."/".$oFile->filename;
        // var_export([$_FILES['file']['tmp_name'], $sFilePath]);
        copy($_FILES['file']['tmp_name'], $sFilePath);
    }

    R::store($oFile);

    die(json_encode(["data" => [ "filePath" => $sRelFilePath ]]));
}
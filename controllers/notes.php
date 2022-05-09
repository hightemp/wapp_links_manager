<?php

// R::fancyDebug( TRUE );

if ($sMethod == 'list_notes') {
    $sOffset = fnPagination($aRequest['page'], $aRequest['rows']);
    $aResult = [];

    $aNotes = R::findAll(T_NOTES, "ORDER BY id DESC {$sOffset}", []);

    foreach ($aNotes as $oNote) {
        $oNote->count = $oNote->countOwn(T_LINKS);
    }

    $aResult['total'] = R::count(T_NOTES);

    $aResult['rows'] = array_values((array) $aNotes);
    
    die(json_encode($aResult));
}

if ($sMethod == 'list_all_notes') {
    $aNotes = R::findAll(T_NOTES, "ORDER BY id DESC", []);

    // foreach ($aNotes as $oNote) {
    //     $oNote->text = $oNote->name;
    // }

    die(json_encode(array_values((array) $aNotes)));
}

if ($sMethod == 'delete_note') {
    $oNote = R::findOne(T_NOTES, "id = ?", [$aRequest['id']]);

    fnBuildRecursiveLinksTreeDelete($oNote);

    die(json_encode([]));
}

if ($sMethod == 'update_note') {
    $oNote = R::findOne(T_NOTES, "id = ?", [$aRequest['id']]);

    $oNote->updated_at = date("Y-m-d H:i:s");
    $oNote->name = $aRequest['name'];
    $oNote->description = $aRequest['description'];

    R::store($oNote);

    die(json_encode([
        "id" => $oNote->id, 
        "text" => $oNote->text
    ]));
}

if ($sMethod == 'create_note') {    
    $oNote = R::dispense(T_NOTES);

    $oNote->created_at = date("Y-m-d H:i:s");
    $oNote->updated_at = date("Y-m-d H:i:s");
    $oNote->timestamp = time();
    $oNote->name = $aRequest['name'];
    $oNote->description = $aRequest['description'];

    if ($oNote->tnotes) {
        $oNote->tnotes->is_ready = 0;
    }

    R::store($oNote);

    die(json_encode([
        "id" => $oNote->id, 
        "text" => $oNote->text
    ]));
}
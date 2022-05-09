<?php

// R::fancyDebug( TRUE );

if ($sMethod == 'list_links') {
    $sFilterRules = " 1 = 1";
    if (isset($aRequest['filterRules'])) {
        $aRequest['filterRules'] = json_decode($aRequest['filterRules']);
        $sFilterRules = fnGenerateFilterRules($aRequest['filterRules']);
    }

    $sOffset = fnPagination($aRequest['page'], $aRequest['rows']);
    $aResult = [];

    if (isset($aRequest['category_id'])) {
        $aLinks = R::findAll(T_LINKS, "{$sFilterRules} AND tcategories_id = ? ORDER BY id DESC {$sOffset}", [$aRequest['category_id']]);
        $aResult['total'] = R::count(T_LINKS, "{$sFilterRules} AND tcategories_id = ?", [$aRequest['category_id']]);
    } else {
        $aLinks = R::findAll(T_LINKS, "{$sFilterRules} ORDER BY id DESC {$sOffset}", []);
        $aResult['total'] = R::count(T_LINKS, "{$sFilterRules}");
    }

    foreach ($aLinks as $oLink) {
        $oLink->group_id = $oLink->tcategories->tgroups_id;
        $oLink->category_id = $oLink->tcategories_id;
        $oLink->note_id = $oLink->tnotes_id;
    }

    $aResult['rows'] = array_values((array) $aLinks);
    
    die(json_encode($aResult));
}

if ($sMethod == 'delete_link') {
    $oLink = R::findOne(T_LINKS, "id = ?", [$aRequest['id']]);

    fnBuildRecursiveLinksTreeDelete($oLink);

    die(json_encode([]));
}

if ($sMethod == 'update_link') {
    $oLink = R::findOne(T_LINKS, "id = ?", [$aRequest['id']]);

    $oLink->updated_at = date("Y-m-d H:i:s");
    $oLink->name = $aRequest['name'];
    $oLink->description = $aRequest['description'];
    $oLink->url = $aRequest['url'];
    $oLink->tcategories = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['category_id']]);
    $oLink->tnotes = R::findOne(T_NOTES, "id = ?", [$aRequest['note_id']]);

    $aParsed = parse_url($oLink->url);
    $oDomain = R::findOrCreate(T_DOMAINS, ["name" => $aParsed["host"]]);
    $oDomain->name = $aParsed["host"];
    
    if (!$oDomain->description) {
        $oDomain->description = "";
    }

    if (!$oDomain->created_at) {
        $oDomain->created_at = date("Y-m-d H:i:s");
        $oDomain->updated_at = date("Y-m-d H:i:s");
        $oDomain->timestamp = time();
    }

    $oDomain->sharedTlinksList[] = $oLink;

    R::store($oDomain);

    R::store($oLink);

    die(json_encode([
        "id" => $oLink->id, 
        "text" => $oLink->text
    ]));
}

if ($sMethod == 'create_link') {    
    $oLink = R::dispense(T_LINKS);

    $oLink->created_at = date("Y-m-d H:i:s");
    $oLink->updated_at = date("Y-m-d H:i:s");
    $oLink->timestamp = time();
    $oLink->name = $aRequest['name'];
    $oLink->description = $aRequest['description'];
    $oLink->url = $aRequest['url'];
    $oLink->tcategories = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['category_id']]);
    $oLink->tnotes = R::findOne(T_NOTES, "id = ?", [$aRequest['note_id']]);

    if ($oLink->tlinks) {
        $oLink->tlinks->is_ready = 0;
    }

    $aParsed = parse_url($oLink->url);
    $oDomain = R::findOrCreate(T_DOMAINS, ["name" => $aParsed["host"]]);
    $oDomain->name = $aParsed["host"];
    
    if (!$oDomain->description) {
        $oDomain->description = "";
    }

    if (!$oDomain->created_at) {
        $oDomain->created_at = date("Y-m-d H:i:s");
        $oDomain->updated_at = date("Y-m-d H:i:s");
        $oDomain->timestamp = time();
    }

    $oDomain->sharedTlinksList[] = $oLink;

    R::store($oDomain);

    R::store($oLink);

    die(json_encode([
        "id" => $oLink->id, 
        "text" => $oLink->text
    ]));
}

if ($sMethod == 'update_or_create_link') {
    $oLink = R::findOrCreate(T_LINKS, ["url" => $aRequest['url']]);

    if (!$oLink->created_at) {
        $oLink->created_at = date("Y-m-d H:i:s");
        $oLink->updated_at = date("Y-m-d H:i:s");
        $oLink->timestamp = time();
    }

    $oLink->name = $aRequest['name'];
    $oLink->description = $aRequest['description'];
    $oLink->url = $aRequest['url'];
    $oLink->tcategories = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['category_id']]);
    $oLink->tnotes = R::findOne(T_NOTES, "id = ?", [$aRequest['note_id']]);

    R::store($oLink);

    die(json_encode([
        "id" => $oLink->id, 
        "text" => $oLink->text
    ]));
}

if ($sMethod == 'move_to_root_link') {
    $oLink = R::findOne(T_LINKS, "id = ?", [$aRequest['id']]);

    $oLink->tlinks = NULL;

    R::store($oLink);

    die(json_encode([
        "id" => $oLink->id, 
        "text" => $oLink->text
    ]));
}
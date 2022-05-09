<?php

// R::fancyDebug( TRUE );

if ($sMethod == 'scan_for_domains') {
    $aResult = [];

    $aLinks = R::findAll(T_LINKS);

    foreach ($aLinks as $oLink) {
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
    }

    $aDomains = R::findAll(T_DOMAINS);

    die(json_encode($aDomains));
}

if ($sMethod == 'list_domains') {
    $sOffset = fnPagination($aRequest['page'], $aRequest['rows']);
    $aResult = [];

    $aDomains = R::findAll(T_DOMAINS, "ORDER BY id DESC {$sOffset}", []);

    foreach ($aDomains as $oDomain) {
        $oDomain->count = $oDomain->countShared(T_LINKS);
    }

    $aResult['total'] = R::count(T_DOMAINS);

    $aResult['rows'] = array_values((array) $aDomains);
    
    die(json_encode($aResult));
}

if ($sMethod == 'list_all_domains') {
    $aDomains = R::findAll(T_DOMAINS, "ORDER BY id DESC", []);

    // foreach ($aDomains as $oDomain) {
    //     $oDomain->text = $oDomain->name;
    // }

    die(json_encode(array_values((array) $aDomains)));
}

if ($sMethod == 'delete_domain') {
    $oDomain = R::findOne(T_DOMAINS, "id = ?", [$aRequest['id']]);

    fnBuildRecursiveLinksTreeDelete($oDomain);

    die(json_encode([]));
}

if ($sMethod == 'update_domain') {
    $oDomain = R::findOne(T_DOMAINS, "id = ?", [$aRequest['id']]);

    $oDomain->updated_at = date("Y-m-d H:i:s");
    $oDomain->name = $aRequest['name'];
    $oDomain->description = $aRequest['description'];

    R::store($oDomain);

    die(json_encode([
        "id" => $oDomain->id, 
        "text" => $oDomain->text
    ]));
}

if ($sMethod == 'create_domain') {    
    $oDomain = R::dispense(T_DOMAINS);

    $oDomain->created_at = date("Y-m-d H:i:s");
    $oDomain->updated_at = date("Y-m-d H:i:s");
    $oDomain->timestamp = time();
    $oDomain->name = $aRequest['name'];
    $oDomain->description = $aRequest['description'];

    if ($oDomain->tdomains) {
        $oDomain->tdomains->is_ready = 0;
    }

    R::store($oDomain);

    die(json_encode([
        "id" => $oDomain->id, 
        "text" => $oDomain->text
    ]));
}
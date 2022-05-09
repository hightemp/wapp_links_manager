<?php

// if ($sMethod == 'list_tree_groups') {
//     if (isset($aRequest['group_id'])) {
//         $aGroups = R::findAll(T_GROUPS, 'tgroups_id = ?', [$aRequest['group_id']]);
//     } else {
//         $aGroups = R::findAll(T_GROUPS, 'tgroups_id IS NULL');
//     }
//     $aResult = [];

//     fnBuildRecursiveCategoriesTree($aResult, $aGroups);

//     die(json_encode(array_values($aResult)));
// }

if ($sMethod == 'list_groups') {
    $aGroups = R::findAll(T_GROUPS);
    $aResult = [];

    foreach ($aGroups as $oGroup) {
        $aResult[] = [
            "id" => $oGroup->id,
            "text" => $oGroup->name,
            "name" => $oGroup->name,
            "description" => $oGroup->description,
            'count' => $oGroup->countOwn(T_CATEGORIES)
        ];
    }

    die(json_encode(array_values($aResult)));
}

if ($sMethod == 'get_group') {
    $aResponse = R::findOne(T_GROUPS, "id = ?", [$aRequest['id']]);
    die(json_encode($aResponse));
}

if ($sMethod == 'delete_group') {
    $oGroup = R::findOne(T_GROUPS, "id = ?", [$aRequest['id']]);

    fnBuildRecursiveCategoriesTreeDelete($oGroup);

    die(json_encode([]));
}

if ($sMethod == 'update_group') {
    $oGroup = R::findOne(T_GROUPS, "id = ?", [$aRequest['id']]);

    $oGroup->name = $aRequest['name'];
    $oGroup->description = $aRequest['description'];

    if (isset($aRequest['group_id']) && !empty($aRequest['group_id'])) {
        $oGroup->tgroups = R::findOne(T_GROUPS, "id = ?", [$aRequest['group_id']]);
    }

    R::store($oGroup);

    die(json_encode([
        "id" => $oGroup->id, 
        "name" => $oGroup->name
    ]));
}

if ($sMethod == 'create_group') {
    $oGroup = R::dispense(T_GROUPS);

    $oGroup->name = $aRequest['name'];
    $oGroup->description = $aRequest['description'];

    if (isset($aRequest['group_id']) && !empty($aRequest['group_id'])) {
        $oGroup->tgroups = R::findOne(T_GROUPS, "id = ?", [$aRequest['group_id']]);
    }

    R::store($oGroup);

    die(json_encode([
        "id" => $oGroup->id, 
        "name" => $oGroup->name
    ]));
}

if ($sMethod == 'move_to_root_group') {
    $oGroup = R::findOne(T_GROUPS, "id = ?", [$aRequest['id']]);

    $oGroup->tgroups = NULL;

    R::store($oGroup);

    die(json_encode([
        "id" => $oGroup->id, 
        "text" => $oGroup->name
    ]));
}
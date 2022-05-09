<?php

// Таблицы

if ($sMethod == 'list_tree_categories') {
    if (isset($aRequest['group_id']) && $aRequest['group_id']>0) {
        $aCategories = R::findAll(T_CATEGORIES, 'tgroups_id = ? AND tcategories_id IS NULL', [$aRequest['group_id']]);
    } else {
        $aCategories = R::findAll(T_CATEGORIES, 'tcategories_id IS NULL');
    }
    $aResult = [];

    fnBuildRecursiveCategoriesTree($aResult, $aCategories);

    die(json_encode(array_values($aResult)));
}

if ($sMethod == 'list_categories') {
    $aResponse = R::findAll(T_CATEGORIES);
    die(json_encode(array_values($aResponse)));
}

if ($sMethod == 'get_category') {
    $aResponse = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['id']]);
    die(json_encode($aResponse));
}

if ($sMethod == 'delete_category') {
    $oCategory = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['id']]);

    fnBuildRecursiveCategoriesTreeDelete($oCategory);

    die(json_encode([]));
}

if ($sMethod == 'update_category') {
    $oCategory = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['id']]);

    $oCategory->name = $aRequest['name'];
    $oCategory->description = $aRequest['description'];

    if (isset($aRequest['group_id']) && !empty($aRequest['group_id'])) {
        $oCategory->tgroups = R::findOne(T_GROUPS, "id = ?", [$aRequest['group_id']]);
    } else {
        $oCategory->tgroups_id = null;
    }

    if (isset($aRequest['category_id']) && !empty($aRequest['category_id'])) {
        $oCategory->tcategories = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['category_id']]);
    } else {
        $oCategory->tcategories_id = null;
    }

    R::store($oCategory);

    die(json_encode([
        "id" => $oCategory->id, 
        "name" => $oCategory->name
    ]));
}

if ($sMethod == 'create_category') {
    $oCategory = R::dispense(T_CATEGORIES);

    $oCategory->name = $aRequest['name'];
    $oCategory->description = $aRequest['description'];

    if (isset($aRequest['group_id']) && !empty($aRequest['group_id'])) {
        $oCategory->tgroups = R::findOne(T_GROUPS, "id = ?", [$aRequest['group_id']]);
    }

    if (isset($aRequest['category_id']) && !empty($aRequest['category_id'])) {
        $oCategory->tcategories = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['category_id']]);
    }

    R::store($oCategory);

    die(json_encode([
        "id" => $oCategory->id, 
        "name" => $oCategory->name
    ]));
}

if ($sMethod == 'move_to_root_category') {
    $oCategory = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['id']]);

    $oCategory->tcategories = NULL;

    R::store($oCategory);

    die(json_encode([
        "id" => $oCategory->id, 
        "text" => $oCategory->name
    ]));
}
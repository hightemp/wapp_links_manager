<?php

function fnGetTitleFromURL($sLink) 
{
    $ch = curl_init();
    curl_setopt_array(
        $ch,
        [
            CURLOPT_URL => $sLink,
            CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
            CURLOPT_HEADER => 0,
            CURLOPT_ENCODING=>'gzip, deflate',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HTTPHEADER=>array(
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language: en-US,en;q=0.5',
                    'Accept-Encoding: gzip, deflate',
                    'Connection: keep-alive',
                    'Upgrade-Insecure-Requests: 1',
            ),
        ]
    );
    $sHTML = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    preg_match("@<title>([^<]*?)</title>@", $sHTML, $aM);
    return $aM[1] ?: "";
}

function fnGenerateFilterRules($aFilterRules)
{
    $sSQL = "";

    foreach ($aFilterRules as $aRule) {
        $aRule = (array) $aRule;
        if ($aRule["op"] == "contains") {
            $sSQL .= " {$aRule["field"]} LIKE '%{$aRule["value"]}%' ";
        }
    }

    return $sSQL;
}

function fnPagination($iPage, $iRows)
{
    $iF = ($iPage-1)*$iRows;
    return " LIMIT {$iF}, {$iRows}";
}

function fnBuildRecursiveCategoriesTree(&$aResult, $aCategories) 
{
    $aResult = [];

    foreach ($aCategories as $oCategory) {
        $aTreeChildren = [];

        $aChildren = R::findAll(T_CATEGORIES, " tcategories_id = {$oCategory->id}");
        fnBuildRecursiveCategoriesTree($aTreeChildren, $aChildren);

        $aResult[] = [
            'id' => $oCategory->id,
            'text' => $oCategory->name,
            'name' => $oCategory->name,
            'description' => $oCategory->description,
            'category_id' => $oCategory->tcategories_id,
            'group_id' => $oCategory->tgroups_id,
            'children' => $aTreeChildren,
            'count' => $oCategory->countOwn(T_LINKS)
        ];
    }
}

function fnBuildRecursiveLinksTree(&$aResult, $aLinks, $sSQL = "", $aBindings=[]) 
{
    $aResult = [];

    foreach ($aLinks as $oLink) {
        $aTreeChildren = [];

        $aChildren = R::findAll(T_LINKS, " tlinks_id = {$oLink->id} {$sSQL}", $aBindings);
        fnBuildRecursiveLinksTree($aTreeChildren, $aChildren, $sSQL, $aBindings);
        $iC = $oLink->countOwn(T_LINKS);

        $aResult[] = [
            'id' => $oLink->id,
            'text' => $oLink->name,
            'created_at' => $oLink->created_at,
            'is_ready' => $oLink->is_ready,
            'name' => $oLink->name,
            'description' => $oLink->description,
            'category_id' => $oLink->tcategories_id,
            'task_id' => $oLink->tlinks_id,
            'children' => $aTreeChildren,
            'notes_count' => $iC,
            'checked' => $oLink->is_ready == '1',
            // 'state' => $iC > 0 ? "closed" : "",
        ];
    }
}

// function fnBuildRecursiveLinksTreeModify($oLink, $bIsReady) 
// {
//     $aChildren = R::findAll(T_LINKS, " tlinks_id = {$oLink->id}");

//     foreach ($aChildren as $oChildLink) {
//         $oChildLink->is_ready = $bIsReady;
//         R::store($oChildLink);

//         fnBuildRecursiveLinksTreeModify($oChildLink, $bIsReady);
//     }
// }

function fnBuildRecursiveLinksTreeDelete($oLink) 
{
    $aChildren = R::findAll(T_LINKS, " tlinks_id = {$oLink->id}");

    foreach ($aChildren as $oChildLink) {
        fnBuildRecursiveLinksTreeDelete($oChildLink);
        R::trashBatch(T_LINKS, [$oChildLink->id]);
    }

    R::trashBatch(T_LINKS, [$oLink->id]);
}

function fnBuildRecursiveCategoriesTreeDelete($oCategory) 
{
    $aChildren = R::findAll(T_CATEGORIES, " tcategories_id = {$oCategory->id}");

    foreach ($aChildren as $oChildCategory) {
        fnBuildRecursiveCategoriesTreeDelete($oChildCategory);
        R::trashBatch(T_CATEGORIES, [$oChildCategory->id]);
    }

    $aLinks = R::findAll(T_LINKS, " tcategories_id = {$oCategory->id}");

    foreach ($aLinks as $oChildLink) {
        fnBuildRecursiveLinksTreeDelete($oChildLink);
    }

    R::trashBatch(T_CATEGORIES, [$oCategory->id]);
}
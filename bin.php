<?php 

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

include_once("./database.php");
include_once("./lib.php");

if ($argv[1] == "nuke") {
    R::nuke();
    die();
}

if ($argv[1] == "truncate_category") {
    R::wipe(T_CATEGORIES);
    die();
}

if ($argv[1] == "list_tables") {
    $listOfTables = R::inspect();
    die(json_encode($listOfTables));
}

if ($argv[1] == "list_fields") {
    $fields = R::inspect($argv[2]);
    die(json_encode($fields));
}

if ($argv[1] == "test_get_title") {
    echo fnGetTitleFromURL("https://www.google.com");
    echo fnGetTitleFromURL("https://hh.ru/calendar");
    echo fnGetTitleFromURL("https://ru.investing.com/currencies/usd-rub");
    die("\n\n\n");
}

if ($argv[1] == "telegram_get_updates") {
    $KEY = Config::$aOptions["telegram"]["API"];
    $iOffset = file_get_contents("/tmp/offset.txt");
    $sURL = "https://api.telegram.org/bot{$KEY}/getUpdates";

    echo $sURL;
    echo "\n\n";

    $sContent = file_get_contents($sURL);
    $aResponse = json_decode($sContent, true);
    
    echo json_encode($aResponse, JSON_PRETTY_PRINT);

    die();
}

if ($argv[1] == "telegram_get_photo") {
    $KEY = Config::$aOptions["telegram"]["API"];
    $iOffset = file_get_contents("/tmp/offset.txt");
    $sURL = "https://api.telegram.org/bot{$KEY}/getFile?file_id=AgACAgIAAxkBAAPFYn90dz0mHnFfj8ZxSYnMZuYe95YAAlnAMRuB49hLF9JfclykodIBAAMCAANzAAMkBA";

    echo $sURL;
    echo "\n\n";

    $sContent = file_get_contents($sURL);
    $aResponse = json_decode($sContent, true);
    
    echo json_encode($aResponse, JSON_PRETTY_PRINT);

    die();
}

if ($argv[1] == "create_demo_data") {
    // R::nuke();
    // exec('rm -f ./sql/*.sql');

    $aLinks = [
        'https://www.jeasyui.com/documentation/index.php#',
        'https://books.google.ru/books?id=8VUHCgAAQBAJ&pg=PA226&lpg=PA226&dq=jquery&source=bl&ots=ou0OKvB-Qs&sig=ACfU3U3Qe6h3df3NyP12C7iPOjxmPLFRJQ&hl=ru&sa=X&ved=2ahUKEwinpODD6pv3AhWTn4sKHfkwCaYQ6AF6BQj4AhAD',
        'https://www.jeasyui.com/documentation/index.php/sadfasdfdsafasfdsafdsafsaf/sadfsafdsafdasfdasf/sadfasdfsaf/',
    ];

    $aTitles = [
        'jQuery => событие, подготовленное документами',
        'Пользовательский интерфейс jQuery 1.10.3 в сети доставки содержимого Microsoft Ajax',
        '8.8. Работа с javascript/jQuery в Drupal 8. Что такое behaviors?',
    ];

    $aCatTitles = [
        'Тестовая категория 1',
        'Очень длинный заголовок',
        'Корот. загол.',
    ];

    $aCategories = [
        null,
    ];

    $aNotes = [
        null,
    ];

    for ($i=0; $i<100; $i++) {
        $oNote = R::dispense(T_NOTES);

        $oNote->created_at = date("Y-m-d H:i:s");
        $oNote->updated_at = date("Y-m-d H:i:s");
        $oNote->timestamp = time();
        $oNote->name = 'Найти бла бла бла бла бла бла бла бла бла бла бла бла '.$i;
        $oNote->description = 'Найти бла бла бла бла бла бла бла бла бла бла бла бла';

        R::store($oNote);

        $aNotes[] = $oNote;
    }

    $aGroups = [];

    for ($i=0; $i<50; $i++) {
        $oGroup = R::dispense(T_GROUPS);

        $oGroup->name = 'Тестовая группа '.$i;
        $oGroup->description = 'Тестовое описание';

        R::store($oGroup);

        $aGroups[] = $oGroup;
    }

    for ($i=0; $i<100; $i++) {
        $oCategory = R::dispense(T_CATEGORIES);

        $oCategory->name = $aCatTitles[random_int(0, 2)];
        $oCategory->description = 'Тестовая категория';

        $oCategory->tgroups = $aGroups[random_int(0, count($aGroups)-1)];
        $iC = $oCategory->tgroups->countOwn(T_CATEGORIES);
        if ($iC) {
            $iI = random_int(0, $iC-1);
            $oCategory->tcategories = $oCategory->tgroups->ownCategoriesList[$iI];
        } else {
            // $oCategory->tcategories_id = null;
        }

        R::store($oCategory);

        $aCategories[] = $oCategory;
    }

    for ($i=0; $i<200; $i++) {
        $oLink = R::dispense(T_LINKS);

        $oLink->created_at = date("Y-m-d H:i:s");
        $oLink->updated_at = date("Y-m-d H:i:s");
        $oLink->timestamp = time();
        $oLink->name = $aTitles[random_int(0, 2)];
        $oLink->url = $aLinks[random_int(0, 2)];
        $oLink->description = $aTitles[random_int(0, 2)];
        $oLink->tnotes = $aNotes[random_int(0, count($aNotes)-1)];
        $oLink->tcategories = $aCategories[random_int(0, count($aCategories)-1)];

        // if ($oLink->tcategories) {
        //     $iC = $oLink->tcategories->countOwn(T_CATEGORIES);
        //     if ($iC) {
        //         $iI = random_int(0, $iC-1);
        //         $oLink->tcategories = $oCategory->tcategories->ownCategoriesList[$iI];
        //     } else {
        //         $oLink->tcategories = null;
        //     }
        // }

        R::store($oLink);
    }
}

if ($argv[1] == "create_scheme") {
    R::nuke();
    exec('rm -f ./sql/*.sql');

    $oNote = R::dispense(T_NOTES);

    $oNote->created_at = date("Y-m-d H:i:s");
    $oNote->updated_at = date("Y-m-d H:i:s");
    $oNote->timestamp = time();
    $oNote->name = 'Найти бла бла бла бла бла бла бла бла бла бла бла бла '.$i;
    $oNote->description = 'Найти бла бла бла бла бла бла бла бла бла бла бла бла';

    R::store($oNote);

    $oGroup = R::dispense(T_GROUPS);

    $oGroup->name = 'Тестовая группа';
    $oGroup->description = 'Тестовое описание';

    R::store($oGroup);

    $oCategory = R::dispense(T_CATEGORIES);

    $oCategory->name = 'Тестовая категория';
    $oCategory->description = 'Тестовая категория';

    $oCategory2 = R::dispense(T_CATEGORIES);
    $oCategory2->name = 'Тестовая категория 2';
    $oCategory2->description = 'Тестовая категория 2';

    $oCategory->tgroups = $oGroup;
    $oCategory->tcategories = $oCategory2;

    R::store($oCategory);


    $oLink = R::dispense(T_LINKS);

    $oLink->created_at = date("Y-m-d H:i:s");
    $oLink->updated_at = date("Y-m-d H:i:s");
    $oLink->timestamp = time();
    $oLink->name = 'Тестовая заметка';
    $oLink->url = 'http://test.ru/';
    $oLink->description = 'Тестовая заметка';
    $oLink->tcategories = $oCategory;
    $oLink->tnotes = $oNote;

    R::store($oLink);

    $oDomain = R::dispense(T_DOMAINS);

    $oDomain->created_at = date("Y-m-d H:i:s");
    $oDomain->updated_at = date("Y-m-d H:i:s");
    $oDomain->timestamp = time();
    $oDomain->name = 'Тестовая заметка';
    $oDomain->url = 'http://test.ru/';
    $oDomain->description = 'Тестовая заметка';
    $oDomain->sharedTlinksList[] = $oLink;

    R::store($oDomain);

    $oTag = R::dispense(T_TAGS);

    $oTag->created_at = date("Y-m-d H:i:s");
    $oTag->updated_at = date("Y-m-d H:i:s");
    $oTag->timestamp = time();
    $oTag->name = 'Тестовый тэг';

    R::store($oTag);

    $oTagToObjects = R::dispense(T_TAGS_TO_OBJECTS);

    $oTagToObjects->ttags = $oTag;
    $oTagToObjects->content_id = $oNote->id;
    $oTagToObjects->content_type = 'tnotes';
    $oTagToObjects->poly('contentType');

    R::store($oTagToObjects);

    R::trashBatch(T_NOTES, [$oNote->id]);
    R::trashBatch(T_LINKS, [$oLink->id]);
    R::trashBatch(T_DOMAINS, [$oDomain->id]);
    R::trashBatch(T_GROUPS, [$oGroup->id]);
    R::trashBatch(T_CATEGORIES, [$oCategory->id]);
    R::trashBatch(T_CATEGORIES, [$oCategory2->id]);
    R::trashBatch(T_TAGS, [$oTag->id]);
    R::trashBatch(T_TAGS_TO_OBJECTS, [$oTagToObjects->id]);

    die(json_encode([]));
}

// function fnBuildRecursiveCategoriesTree(&$aResult, $aCategories) 
// {
//     $aResult = [];

//     foreach ($aCategories as $oCategory) {
//         $aTreeChildren = [];

//         $aChildren = R::children($oCategory, " id != {$oCategory->id}");
//         fnBuildRecursiveCategoriesTree($aTreeChildren, $aChildren);

//         $aResult[] = [
//             'id' => $oCategory->id,
//             'text' => $oCategory->name,
//             'children' => $aTreeChildren,
//             'notes_count' => $oCategory->countOwn(T_TASKS)
//         ];
//     }
// }

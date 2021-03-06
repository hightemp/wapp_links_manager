<?php

// hightemp_links_helper_bot
$KEY = Config::$aOptions["telegram"]["API"];

$iOffset = file_get_contents("/tmp/offset.txt");

$sURL = "https://api.telegram.org/bot{$KEY}/getUpdates?offset=$iOffset";

// this would bring the latest message from telegram group
$sContent = file_get_contents($sURL);

error_log($sContent, 3, "./logs/telegram_messages.log");

$aResponse = json_decode($sContent, true);
$aMSG_List = $aResponse['result'];

foreach ($aMSG_List as $aMSG) {

    $iOffset = $aMSG['update_id']+1; 
    $sMSG = $aMSG['message']['text'] ?: $aMSG['message']['caption'];
    $iChatID = $aMSG['message']['chat']['id'];

    $bIsBot = $aMSG['message']['form']['is_bot'];

    if ($bIsBot) continue;

    $sResponseURL = "https://api.telegram.org/bot{$KEY}/sendMessage?chat_id={$iChatID}&text=";

    $sTestingMSG = trim($sMSG);

    if (!$sTestingMSG) {
        // $sTestingMSG = json_encode($aMSG);
        continue;
    }

    if ($aMSG['message']['photo']) {
        foreach ($aMSG['message']['photo'] as $aPhoto) {
            $iFileID = $aPhoto["file_id"];
            $sPhotoInfoURL = "https://api.telegram.org/bot{$KEY}/getFile?file_id={$iFileID}";

            $sJSON = file_get_contents($sPhotoInfoURL);
            $aFileInfo = json_decode($sJSON, true);
            $sFilePath = $aFileInfo["result"]["file_path"];

            $sFileURL = "https://api.telegram.org/file/bot{$KEY}/{$sFilePath}";

            $sData = file_get_contents($sFileURL);

            preg_match("\.(\w+)$", $sFilePath, $aMExt);

            $oFile = R::dispense(T_FILES);

            $oFile->created_at = date("Y-m-d H:i:s");
            $oFile->updated_at = date("Y-m-d H:i:s");
            $oFile->timestamp = time();

            $sFilePath = $sFUI."/".$oFile->timestamp.".".$aMExt[1];
            file_put_contents($sFilePath, $sData);

            $oFile->name = $iFileID;

            $oFile->type = $aMExt[1];
            $oFile->filename = $oFile->timestamp.".".@$aM[1];

            $sFilePath = $sFIP."/".$oFile->filename;
            $sRelFilePath = $sIP."/".$oFile->filename;
            // var_export([$_FILES['file']['tmp_name'], $sFilePath]);
            // copy($_FILES['file']['tmp_name'], $sFilePath);

            R::store($oFile);
        }
    }

    if (preg_match_all('@(https?://[^\s]+)@', $sTestingMSG, $aM)) {
        foreach ($aM[1] as $sLink) {
            $sTitle = fnGetTitleFromURL($sLink);

            $oLink = R::findOne(T_LINKS, "url = ?", [$sLink]);

            if ($oLink) {
                continue;
            }

            $oLink = R::dispense(T_LINKS);

            $oLink->created_at = date("Y-m-d H:i:s");
            $oLink->updated_at = date("Y-m-d H:i:s");
            $oLink->timestamp = time();
            $oLink->name = $sTitle;
            $oLink->description = $sMSG;
            $oLink->url = $sLink;
            // $oLink->tcategories = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['category_id']]);
            // $oLink->tnotes = R::findOne(T_NOTES, "id = ?", [$aRequest['note_id']]);

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

            // $oDomain = convert_from_latin1_to_utf8_recursively($oDomain);
            // $oLink = convert_from_latin1_to_utf8_recursively($oLink);

            R::store($oDomain);
            R::store($oLink);

            // file_put_contents("/var/www/test.txt", json_encode($oLink->id));

            $ID = $_SERVER['SERVER_ADDR']." ".$_SERVER['SERVER_NAME'];
            $sBadLink = preg_replace('@https?://@i', "", $sLink);
            $sText = urlencode("{$ID} - added URL: \n{$sTitle}\n{$sBadLink}");
            file_get_contents($sResponseURL.$sText);

            echo "\n\n".$sResponseURL.$sText."\n\n";
        }
    } else {
        $oNote = R::dispense(T_NOTES);

        $oNote->created_at = date("Y-m-d H:i:s");
        $oNote->updated_at = date("Y-m-d H:i:s");
        $oNote->timestamp = time();
        $oNote->name = $sMSG;
        $oNote->description = $sMSG;
    
        if ($oNote->tnotes) {
            $oNote->tnotes->is_ready = 0;
        }
    
        R::store($oNote);

        $ID = $_SERVER['SERVER_ADDR']." ".$_SERVER['SERVER_NAME'];
        $sText = urlencode("{$ID} - added note: \n{$sMSG}");
        file_get_contents($sResponseURL.$sText);

        echo "\n\n".$sResponseURL.$sText."\n\n";
    }

    echo "\n\nOFFSET: {$iOffset}\n\n";
    file_put_contents("/tmp/offset.txt", $iOffset);

    error_log(json_encode($sMSG), 3, "./logs/telegram.log");
}
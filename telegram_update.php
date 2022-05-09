<?php

// hightemp_links_helper_bot
$KEY = Config::$aOptions["telegram"]["API"];

// if (isset($aConfig["message"])) {
//     $chatID = $aConfig["message"]["chat"]["id"];
//     $text = $aConfig["message"]["text"];
//     $message_id = $aConfig["message"]["message_id"];

//     if ($text == '/start') {
//         // send welcome message
//         $sMessage = file_get_contents($website . "sendMessage?chat_id=" . $chatID . "&text=Send your Text for Converting to Lowercase");
//     } else {
//         $text = mb_strtolower($text, "UTF-8");
//         $sMessage = file_get_contents($website . "sendMessage?chat_id=" . $chatID . "&text=$text&reply_to_message_id=$message_id");
//     }
// }

$iOffset = 0; @file_get_contents("/tmp/offset.txt");

$sURL = "https://api.telegram.org/bot{$KEY}/getUpdates?offset=$iOffset";

// this would bring the latest message from telegram group
$sContent = file_get_contents($sURL);
$aResponse = json_decode($sContent, true);
$aMSG_List = $aResponse['result'];
$aMSG = end($aMSG_List);
$iOffset= $aMSG['update_id']+1; 
$sMSG = $aMSG['message']['text'];

if (preg_match_all('@(https?://[^\s]+)@', $sMSG, $aM)) {
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
        $oLink->description = '';
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

        R::store($oDomain);
        R::store($oLink);

        // file_put_contents("/var/www/test.txt", json_encode($oLink->id));
    }
}

file_put_contents("/tmp/offset.txt", $iOffset);

// file_put_contents("./test.txt", json_encode($sMSG));
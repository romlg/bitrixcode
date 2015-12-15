<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$sections = array();
$arResult["DIRECTION"] = array();

$some_elem = end($arResult["ITEMS"]);
if ($some_elem && $some_elem["ID"]) {
    $arResult["DIRECTION"] = DrclinicsServicesHelper::getSefviceMediaspect($some_elem["ID"]);
    $direction_id = $arResult["DIRECTION"]["ID"];
} else {
    $direction_id = 0;
}

foreach($arResult["ITEMS"] as $r_item) {
    $subdirection = false;
    $section_id = $r_item["~IBLOCK_SECTION_ID"];
    if ($section_id && $direction_id) {
        $nav = CIBlockSection::GetNavChain(false, $section_id);
        $old_path_id = false;
        while (!$subdirection && ($arSectionPath = $nav->GetNext())) {
            if ($old_path_id == $direction_id) {
                $subdirection = $arSectionPath;
            }
            $old_path_id = $arSectionPath["ID"];
        }
    }

    if ($subdirection) {
        $section_id = $subdirection["ID"];
    }
    if (!$section_id) {
        $section_id = 0;
    }

    if (!isset($sections[$section_id]))
    {
        if($subdirection) {
            $sections[$section_id] = array(
                "ID" => $subdirection['ID'],
                "SORT" => $subdirection['SORT'],
                "XML_ID" => $subdirection['ID'],
                "NAME" => $subdirection['NAME'],
                "ITEMS" => array(),
            );
        } else {
            $sections[$section_id] = array(
                "ID" => 0,
                "SORT" => 0,
                "XML_ID" => 0,
                "NAME" => $arResult["DIRECTION"] && $arResult["DIRECTION"]["NAME"] ? $arResult["DIRECTION"]["NAME"] : "",
                "ITEMS" => array(),
            );
        }
    }
    if ($sections[$section_id]) {
        $sections[$section_id]["ITEMS"][] = $r_item;
    }
}

$arResult["SECTIONS"] = $sections;

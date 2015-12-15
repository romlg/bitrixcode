<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$is_virtual_section = isset($arParams["ALL_IN_ONE_SECTION"]) && $arParams["ALL_IN_ONE_SECTION"]=="Y";
$virtual_section_name = isset($arParams["ONE_SECTION_NAME"]) && $arParams["ONE_SECTION_NAME"] ? $arParams["ONE_SECTION_NAME"] : "";

$sections = array();
foreach($arResult["ITEMS"] as $r_item) {
    if (!isset($sections[$r_item["~IBLOCK_SECTION_ID"]]))
    {
        $res = CIBlockSection::GetByID($r_item["~IBLOCK_SECTION_ID"]);
        if($ar_res = $res->GetNext()) {
            $sections[$r_item["~IBLOCK_SECTION_ID"]] = array(
                "ID" => $ar_res['ID'],
                "IBLOCK_SECTION_ID" => $is_virtual_section ? 0 : intval($ar_res['IBLOCK_SECTION_ID']),
                "NAME" => $is_virtual_section ? $virtual_section_name : $ar_res['NAME'],
                "SORT" => $ar_res['SORT'],
                "ITEMS" => array(),
            );
        } else {
            $sections[$r_item["~IBLOCK_SECTION_ID"]] = false;
        }
    }
    if ($sections[$r_item["~IBLOCK_SECTION_ID"]] !== false) {
        $sections[$r_item["~IBLOCK_SECTION_ID"]]["ITEMS"][] = $r_item;
    }
}

$top_sections = array();
foreach($sections as $r_item) {
    if (!isset($top_sections[$r_item["IBLOCK_SECTION_ID"]]))
    {
        if ($r_item["IBLOCK_SECTION_ID"]) {
            $res = CIBlockSection::GetByID($r_item["IBLOCK_SECTION_ID"]);
            $ar_res = $res->GetNext();
        } else {
            $ar_res = array(
                "ID" => 0,
                "IBLOCK_SECTION_ID" => 0,
                "NAME" => "",
                "SORT" => -1,
                "DESCRIPTION" => "",
            );
        }
        if($ar_res) {
            $top_sections[$r_item["IBLOCK_SECTION_ID"]] = array(
                "ID" => $ar_res['ID'],
                "IBLOCK_SECTION_ID" => $ar_res['IBLOCK_SECTION_ID'],
                "NAME" => $is_virtual_section ? "" : $ar_res['NAME'],
                "SORT" => $ar_res['SORT'],
                "DESCRIPTION" => $ar_res['DESCRIPTION'],
                "SECTIONS" => array(),
            );
        } else {
            $top_sections[$r_item["IBLOCK_SECTION_ID"]] = false;
        }
    }
    if ($top_sections[$r_item["IBLOCK_SECTION_ID"]] !== false) {
        $top_sections[$r_item["IBLOCK_SECTION_ID"]]["SECTIONS"][] = $r_item;
    }
}

$arResult["SECTIONS"] = $top_sections;
DrclinicsHelper::sortItemsList($arResult["SECTIONS"]);

foreach($arResult["SECTIONS"] as &$subsection) {
    DrclinicsHelper::sortItemsList($subsection["SECTIONS"]);
    foreach($subsection["SECTIONS"] as &$subsubsection) {
        DrclinicsHelper::sortItemsList($subsubsection["ITEMS"]);
    }
    unset($subsubsection);
}
unset($subsection);
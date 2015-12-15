<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule("iblock");

if (!isset($arParams['HOME_USE']) || $arParams['HOME_USE']!="Y") {
    DrclinicsServicesHelper::ServicesRedirect();
}

$active_direction = isset($_REQUEST["active_direction"]) && $_REQUEST["active_direction"] ? intval($_REQUEST["active_direction"]) : 0;
if (!$active_direction && ((isset($arParams["ACTIVE_DIRECTION"]) && ($arParams["ACTIVE_DIRECTION"]) || (isset($arParams["ACTIVE_DIRECTION_CODE"]) && $arParams["ACTIVE_DIRECTION_CODE"])))) {
    $active_direction = intval($arParams["ACTIVE_DIRECTION"]);
    if (!$active_direction && $arParams["ACTIVE_DIRECTION_CODE"]) {
        $id = 0;
        $code = $arParams["ACTIVE_DIRECTION_CODE"];
        if($code) {
            if (substr($code, -1) == '/') {
                $code = substr($code, 0, -1);
            }
            $res = CIBlockSection::GetList(
                array(),
                array('IBLOCK_ID'=>DrclinicsHelper::IBLOCK_SERVICES_ID, '=CODE'=>trim($code), "UF_IS_MEDASPECTS"=>"1"),
                false,
                false,
                array('ID', 'NAME')
            );
            if ($obj = $res->GetNext()) {
                $active_direction = $obj['ID'];
            }
        }
    }
}

$parent_id = false;
if (isset($arParams["PARENT_SECTION_ID"])) {
    $parent_id = $arParams["PARENT_SECTION_ID"];
}
$arItems = DrclinicsHelper::getMediaspects(false, NULL, false, $parent_id);
$currentItem = array();
$k = 1;
foreach($arItems as $k=>$arItem) {
    if (isset($arParams['HOME_USE']) && $arParams['HOME_USE']=="Y") {
        if (!$arItem["UF_HOME"]) {
            unset($arItems[$k]);
            continue;
        }
    }
    if (!$active_direction) {
        $active_direction = $arItem["ID"];
    }
    $arItems[$k]["INDEX"] = $k;
    $arItems[$k]["ACTIVE"] = $active_direction == $arItem["ID"];
    if ($arItems[$k]["ACTIVE"]) {
        $currentItem = $arItems[$k];
        if (isset($arParams["ADD_SECTION_CHAIN"]) && $arParams["ADD_SECTION_CHAIN"]=="Y") {
            $APPLICATION->AddChainItem("Услуги и цены", "/nashi-uslugi/med-uslugi/");
            if ($currentItem['NAME']) {
                $APPLICATION->AddChainItem($currentItem['NAME'], $currentItem["SECTION_PAGE_URL"]);
            }
        }
        if (isset($arParams["SET_TITLE"]) && $arParams["SET_TITLE"]=="Y") {
            $currentItem['NAME']=$currentItem['NAME']?$currentItem['NAME']:'Услуги и цены';
            $APPLICATION->SetTitle($currentItem['NAME']);
        }
    }
    $k++;
}

$arResult = $currentItem;

$arResult["FILTERS"] = DrclinicsHelper::getMediaspectFilters(isset($arResult["ID"]) ? $arResult["ID"] : false);

if (isset($_SESSION["PICKED_SERVICES"]) && $_SESSION["PICKED_SERVICES"]) {
    $arResult['PICKED_SERVICES'] = DrclinicsHelper::getServices($_SESSION["PICKED_SERVICES"], false, true);
} else {
    $arResult['PICKED_SERVICES'] = array();
}

$arResult['ITEMS'] = $arItems;
$arResult["TAB"] = isset($_REQUEST["active_tab"]) && $_REQUEST["active_tab"] ? $_REQUEST["active_tab"] : "";
$arResult["VIEW_ALL_LINK"] = isset($arParams['VIEW_ALL_LINK'])?$arParams['VIEW_ALL_LINK']:"Y";

$arResult['SERVICES_ITEMS_COUNT'] = isset($arParams['SERVICES_ITEMS_COUNT']) && $arParams['SERVICES_ITEMS_COUNT']>0 ? $arParams['SERVICES_ITEMS_COUNT'] : 0;
$arResult['DOCTORS_ITEMS_COUNT'] = isset($arParams['DOCTORS_ITEMS_COUNT']) && $arParams['DOCTORS_ITEMS_COUNT']>0 ? $arParams['DOCTORS_ITEMS_COUNT'] : 0;
$arResult['CLINICS_ITEMS_COUNT'] = isset($arParams['CLINICS_ITEMS_COUNT']) && $arParams['CLINICS_ITEMS_COUNT']>0 ? $arParams['CLINICS_ITEMS_COUNT'] : 0;
$arResult['PROGRAMS_ITEMS_COUNT'] = isset($arParams['PROGRAMS_ITEMS_COUNT']) && $arParams['PROGRAMS_ITEMS_COUNT']>0 ? $arParams['PROGRAMS_ITEMS_COUNT'] : 0;

$this->IncludeComponentTemplate();

<?php

class DrclinicsServicesHelper {

    public static function getSefviceMediaspect($service_id, $section_id = false)
    {
        $result = false;
        if (!$section_id) {
            $res = CIBlockElement::GetByID($service_id);
            if($element = $res->GetNext()) {
                $section_id = $element["IBLOCK_SECTION_ID"];
            }
        }
        if ($section_id) {
            $section = DrclinicsHelper::getMediaspects($section_id);
            $section = end($section);
            if ($section) {
                $result = $section;
            } else {
                $nav = CIBlockSection::GetNavChain(false, $section_id, array("ID", "CODE", "SECTION_PAGE_URL", "DEPTH_LEVEL"));
                while (($arSectionPath = $nav->GetNext()) && $arSectionPath["DEPTH_LEVEL"]!=2);
                if ($arSectionPath) {
                    $result = $arSectionPath;
                }
            }
        }
        return $result;
    }

    public static function ServicesRedirect()
    {
        if (isset($_REQUEST["add_service"]) && $_REQUEST["add_service"]) {
            $service = DrclinicsHelper::getServices($_REQUEST["add_service"]);
            $service = end($service);
            if ($service) {
                $section = self::getSefviceMediaspect($service["ID"], $service["IBLOCK_SECTION_ID"]);
                if ($section) {
                    self::ServicesSession($service["ID"], "add");
                    LocalRedirect($section["SECTION_PAGE_URL"]);
                }
            }
        }
    }

    public static function ServicesSession($service_item, $action)
    {
        if ($service_item) {
            if ($action == 'add') {
                $_SESSION['PICKED_SERVICES'][] = $service_item;
                $_SESSION['PICKED_SERVICES'] = array_unique($_SESSION['PICKED_SERVICES']);
            } else {
                $_SESSION['PICKED_SERVICES'] = array_unique($_SESSION['PICKED_SERVICES']);
                $key = array_search($service_item, $_SESSION['PICKED_SERVICES']);
                if ($key!==false) unset($_SESSION['PICKED_SERVICES'][$key]);
            }
        }
    }

    public static function searchServices($query, $nocache = false)
    {
        global $USER;

        $arFilter = Array("IBLOCK_ID"=>DrclinicsHelper::IBLOCK_SERVICES_ID, "ACTIVE"=>'Y', "NAME"=>"%".$query."%");
        $arSelectFields = Array("IBLOCK_ID", "IBLOCK_SECTION_ID", "ID", "NAME", "CODE", "DETAIL_PAGE_URL");
        $arServices = DrclinicsHelper::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields, true), $nocache);

        $results = array();
        foreach($arServices as $arr_item) {
            if (!intval($arr_item["ID"])) continue;
            $arr_item["NAME"] = trim($arr_item["NAME"]);
            $results[$arr_item["NAME"]] = array(
                "IS_SERVICE" => true,
                "NAME" => $arr_item["NAME"],
                "NAME_TEXT" => $arr_item["PRICE_STR_VALUE"] ? $arr_item["NAME"] . " — " . $arr_item["PRICE_STR_VALUE"] : $arr_item["NAME"],
                "VALUE" => $arr_item["ID"],
                "URL" => $arr_item["DETAIL_PAGE_URL"],
                "PRICE" => $arr_item["PRICE_VALUE"],
            );
        }

        ksort($results);
        $results = array_values($results);

        return $results;
    }
}

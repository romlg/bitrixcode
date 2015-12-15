<?php

class DrclinicsHelper
{
    const DEFAULT_CACHE_PERIOD = 1;

    const IBLOCK_CLINICS_ID = 4;
    const IBLOCK_DOCTORS_ID = 24;
    const IBLOCK_SPEC_ID = 18;
    const IBLOCK_SERVICES_ID = 8;
    const IBLOCK_STRUCTURE_ID = 15;
    const IBLOCK_DIRECTIONS_ID = 26;
    const IBLOCK_PROGRAMS_ID = 27;

    const DEFAULT_PRICE_ID = 1;

    public static function getValueFromCache($cache_function, $cache_function_params, $nocache = false) {
        $result = null;
        $obj_cache = new CPHPCache;
        $cache_time = self::DEFAULT_CACHE_PERIOD;
        $cache_id = $cache_function . serialize($cache_function_params);
        $cache_path = '/drclinics/'.$cache_function.'/';
        if (!$nocache && $obj_cache->InitCache($cache_time, $cache_id, $cache_path)) {
            $result = $obj_cache->GetVars();
        } else {
            if ($nocache || $obj_cache->StartDataCache($cache_time, $cache_id, $cache_path)) {
                $result = call_user_func_array(
                    __CLASS__ . '::' . $cache_function,
                    $cache_function_params
                );
                if (!$nocache) $obj_cache->EndDataCache($result);
            }
        }
        return $result;
    }

    private static function get_ib_items_list($arFilter, $arSelectFields, $with_prices = false)
    {
        if(!CModule::IncludeModule("iblock")) return false;
        $arItems = array();
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC", "NAME"=>"ASC"), $arFilter, false, false, $arSelectFields);
        while($item = $res->GetNext()) {
            if ($with_prices) {
                $item["PRICE"] = CCatalogProduct::GetOptimalPrice($item["ID"], self::DEFAULT_PRICE_ID, (isset($GLOBALS['USER']) && $GLOBALS['USER']) ? $GLOBALS['USER']->GetUserGroupArray() : 0);
                if (isset($item["PRICE"]["RESULT_PRICE"]) && $item["PRICE"]["RESULT_PRICE"]) {
                    $item["PRICE_VALUE"] = $item["PRICE"]["RESULT_PRICE"]["BASE_PRICE"];
                    $item["PRICE_STR_VALUE"] = CurrencyFormat($item["PRICE"]["RESULT_PRICE"]["BASE_PRICE"], $item["PRICE"]["RESULT_PRICE"]["CURRENCY"]);
                } else {
                    $item["PRICE_VALUE"] = 0;
                    $item["PRICE_STR_VALUE"] = "";
                }
            }
            $arItems[$item["ID"]] = $item;
        }
        return $arItems;
    }

    private static function get_ib_sections_list($arFilter, $arSelectFields)
    {
        if(!CModule::IncludeModule("iblock")) return false;
        $arItems = array();
        $res = CIBlockSection::GetList(Array("SORT"=>"ASC", "NAME"=>"ASC"), $arFilter, false, $arSelectFields);
        while($item = $res->GetNext()) {
            $arItems[$item["ID"]] = $item;
        }
        return $arItems;
    }

    private static function get_ib_section($id)
    {
        $result = false;
        $res = CIBlockSection::GetByID($id);
        if($ar_res = $res->GetNext()) $result = $ar_res;
        return $result;
    }

    public static function getDirections($direction_ids = false, $nocache = false)
    {
        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_DIRECTIONS_ID, "ACTIVE"=>'Y');
        if ($direction_ids) $arFilter["ID"] = $direction_ids;
        $arSelectFields = Array("ID", "NAME", "IBLOCK_ID", "CODE", "PROPERTY_FOUNDATION", "PROPERTY_DOCTORS", "PROPERTY_SERVICES");
        $arClinics = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields), $nocache);
        return $arClinics;
    }

    public static function getClinics($clinic_ids = false, $nocache = false)
    {
        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_CLINICS_ID, "ACTIVE"=>'Y');
        if ($clinic_ids) $arFilter["ID"] = $clinic_ids;
        $arSelectFields = Array("ID", "IBLOCK_ID", "NAME", "CODE", "PROPERTY_LAT", "PROPERTY_LON", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_WORK_TIME", "PROPERTY_METRO", "DETAIL_PAGE_URL");
        $arClinics = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields), $nocache);
        return $arClinics;
    }

    public static function getDoctors($doctor_ids = false, $structure_ids = false, $nocache = false)
    {
        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_DOCTORS_ID, "ACTIVE"=>'Y');
        if ($doctor_ids) $arFilter["ID"] = $doctor_ids;
        if ($structure_ids) $arFilter["PROPERTY_structure"] = $structure_ids;
        $arSelectFields = Array("ID", "NAME", "PROPERTY_organization", "PROPERTY_department",  "PROPERTY_structure", "PROPERTY_user", "PROPERTY_special", "PROPERTY_medaspects", "PROPERTY_services");
        $arDoctors = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields), $nocache);
        return $arDoctors;
    }

    public static function searchDoctors($fio = false, $nocache = false)
    {
        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_DOCTORS_ID, "ACTIVE"=>'Y');
        if ($fio) $arFilter["NAME"] = "%".$fio."%";
        $arSelectFields = Array("ID", "NAME");
        $arDoctors = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields), $nocache);
        foreach($arDoctors as $k=>$arDoc) {
            $arDoctors[$k] = $arDoc["NAME"];
        }
        return $arDoctors;
    }

    public static function getDoctorsServices($doctor_id, $nocache = false)
    {
        $services = array();
        if ($nocache) {
            if ($doctor_id) {
                $mediaspect_ids = array();
                $service_ids = array();
                $doctors = self::getDoctors($doctor_id, false, $nocache);
                foreach ($doctors as $doctor) {
                    if ($doctor["PROPERTY_MEDASPECTS_VALUE"]) {
                        $mediaspect_ids = array_merge($mediaspect_ids, $doctor["PROPERTY_MEDASPECTS_VALUE"]);
                    }
                    if ($doctor["PROPERTY_SERVICES_VALUE"]) {
                        $service_ids = array_merge($service_ids, $doctor["PROPERTY_SERVICES_VALUE"]);
                    }
                }
                if ($service_ids) {
                    $service_ids = array_unique($service_ids);
                    $services = self::getServices($service_ids, false, $nocache);
                } elseif ($mediaspect_ids) {
                    $mediaspect_ids = array_unique($mediaspect_ids);
                    $services = self::getMediaspectServices($mediaspect_ids, false, $nocache);
                }
            }
        } else {
            $services = self::getValueFromCache("getDoctorsServices", array($doctor_id, true));
        }

        return $services;
    }

    public static function getServicesDoctors($service_ids, $nocache = false)
    {
        if ($nocache) {
            $structure_ids = array();
            $services = self::getServices($service_ids, false, false, $nocache);
            foreach ($services as $service) {
                if ($service["PROPERTY_STRUCTURE_VALUE"]) {
                    $structure_ids = array_merge($structure_ids, $service["PROPERTY_STRUCTURE_VALUE"]);
                }
            }
            $doctors = self::getDoctors(false, $structure_ids, $nocache);
        } else {
            $doctors = self::getValueFromCache("getServicesDoctors", array($service_ids, true));
        }
        return $doctors;
    }

    public static function getSpecialities($spec_ids = false, $nocache = false)
    {
        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_SPEC_ID, "ACTIVE"=>'Y');
        if ($spec_ids) $arFilter["ID"] = $spec_ids;
        $arSelectFields = Array("ID", "NAME", "CODE", "PROPERTY_ORGANIZATION");
        $arSpec = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields), $nocache);
        return $arSpec;
    }

    public static function getServices($services_ids = false, $structure_ids = false, $with_prices = false, $nocache = false)
    {
        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_SERVICES_ID, "ACTIVE"=>'Y');
        if ($services_ids) $arFilter["ID"] = $services_ids;
        if ($structure_ids) {
            $arFilter["PROPERTY_STRUCTURE"] = $structure_ids;
        }
        $arSelectFields = Array("IBLOCK_ID", "ID", "NAME", "CODE", "PROPERTY_FOUNDATION", "PROPERTY_DEPARTMENT", "PROPERTY_SPECIALTY", "PROPERTY_STRUCTURE", "CATALOG_GROUP_1");
        $arServices = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields, $with_prices), $nocache);
        return $arServices;
    }

    public static function getMediaspectServices($mediaspect_ids, $with_prices = false, $nocache = false)
    {
        $arServices = array();
        if (!is_array($mediaspect_ids)) $mediaspect_ids = array($mediaspect_ids);
        $maspects = self::getMediaspects($mediaspect_ids);
        $media_sections = $mediaspect_ids;
        foreach($maspects as $maspect) {
            $l_filter = array("ACTIVE"=>'Y');
            if ($maspect['LEFT_MARGIN']) {
                $l_filter["<LEFT_MARGIN"] = $maspect['LEFT_MARGIN'];
            }
            if ($maspect['RIGHT_MARGIN']) {
                $l_filter[">RIGHT_MARGIN"] = $maspect['RIGHT_MARGIN'];
            }
            $l_media_sections = self::getValueFromCache("get_ib_sections_list", array($l_filter, array("ID")), $nocache);
            $l_media_sections = array_keys($l_media_sections);
            $media_sections = array_merge($media_sections, $l_media_sections);
        }

        if ($media_sections) {
            $media_sections = array_unique($media_sections);
            $arFilter = Array("IBLOCK_ID" => self::IBLOCK_SERVICES_ID, "ACTIVE" => 'Y', "SECTION_ID" => $media_sections, "INCLUDE_SUBSECTIONS" => "Y");
            $arSelectFields = Array("IBLOCK_ID", "ID", "NAME", "CODE", "PROPERTY_FOUNDATION", "PROPERTY_DEPARTMENT", "PROPERTY_SPECIALTY", "PROPERTY_STRUCTURE", "CATALOG_GROUP_1");
            $arServices = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields, $with_prices), true);
        }
        return $arServices;
    }

    public static function getMediaspect($id)
    {
        return self::getValueFromCache("get_ib_section", array($id));
    }

    public static function getMediaspects($mediaspect_ids = false, $doctor_ids = NULL, $nocache = false, $parent_id = false)
    {
        $RIGHT_MARGIN = $LEFT_MARGIN = false;
        if ($parent_id) {
            $res = CIBlockSection::GetList(
                array(),
                array('IBLOCK_ID'=>self::IBLOCK_SERVICES_ID, 'ID'=>trim($parent_id)),
                false,
                false,
                array('ID', 'LEFT_MARGIN', 'RIGHT_MARGIN')
            );
            if ($pobj = $res->GetNext()) {
                $RIGHT_MARGIN = $pobj['RIGHT_MARGIN'];
                $LEFT_MARGIN = $pobj['LEFT_MARGIN'];
            }
        }
        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_SERVICES_ID, "ACTIVE"=>'Y', "UF_IS_MEDASPECTS" => "1");
        if ($LEFT_MARGIN) {
            $arFilter["<LEFT_MARGIN"] = $LEFT_MARGIN;
        }
        if ($RIGHT_MARGIN) {
            $arFilter[">RIGHT_MARGIN"] = $RIGHT_MARGIN;
        }
        if ($mediaspect_ids) $arFilter["ID"] = $mediaspect_ids;
        if (!is_null($doctor_ids)) {
            if (!isset($arFilter["ID"])) $arFilter["ID"] = array();
            $aspects = array();
            $arDoctors = self::getDoctors($doctor_ids, false, $nocache);
            foreach($arDoctors as $doct) {
                if ($doct["PROPERTY_MEDASPECTS_VALUE"]) {
                    if (!is_array($doct["PROPERTY_MEDASPECTS_VALUE"])) $doct["PROPERTY_MEDASPECTS_VALUE"] = array($doct["PROPERTY_MEDASPECTS_VALUE"]);
                    foreach($doct["PROPERTY_MEDASPECTS_VALUE"] as $val) {
                        $aspects[$val] = $val;
                    }
                }
            }
            $arFilter["ID"] = array_merge($arFilter["ID"], $aspects);
        }
        $arSelectFields = Array("IBLOCK_ID", "ID", "SORT", "NAME", "CODE", "LEFT_MARGIN", "RIGHT_MARGIN", "DESCRIPTION", "DESCRIPTION_TYPE", "SECTION_PAGE_URL", "UF_*");
        $arMediaspects = self::getValueFromCache("get_ib_sections_list", array($arFilter, $arSelectFields), $nocache);

        return $arMediaspects;
    }

    private static function appendSearchResults(&$results, $array)
    {
        foreach($array as $arr_item) {
            if (!intval($arr_item["ID"])) continue;
            $arr_item["NAME"] = trim($arr_item["NAME"]);
            $results[$arr_item["NAME"]] = array(
                "IS_SERVICE" => $arr_item["IBLOCK_ID"] == self::IBLOCK_SERVICES_ID && isset($arr_item["DETAIL_PAGE_URL"]),
                "NAME" => $arr_item["NAME"],
                "NAME_TEXT" => $arr_item["PRICE_STR_VALUE"] ? $arr_item["NAME"] . " — " . $arr_item["PRICE_STR_VALUE"] : $arr_item["NAME"],
                "VALUE" => $arr_item["ID"],
                "URL" => isset($arr_item["DETAIL_PAGE_URL"]) ? $arr_item["DETAIL_PAGE_URL"] : @$arr_item["SECTION_PAGE_URL"],
                "PRICE" => $arr_item["PRICE_VALUE"],
            );
        }
    }

    public static function getMediaspectFilters($mediaspect_id, $nocache = false)
    {
        $filters = array();
        $services_clinics = array();
        $inner_services = self::getMediaspectServices($mediaspect_id, false, $nocache);

        foreach($inner_services as $servic) {
            if (isset($servic["PROPERTY_FOUNDATION_VALUE"]) && $servic["PROPERTY_FOUNDATION_VALUE"]) {
                if (!is_array($servic["PROPERTY_FOUNDATION_VALUE"])) $servic["PROPERTY_FOUNDATION_VALUE"] = array($servic["PROPERTY_FOUNDATION_VALUE"]);
                foreach($servic["PROPERTY_FOUNDATION_VALUE"] as $val) {
                    $services_clinics[$val] = $val;
                }
            }
        }
        $inner_services = array_keys($inner_services);

        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_DOCTORS_ID, "ACTIVE"=>'Y', array(
            "LOGIC" => "OR",
            "PROPERTY_services" => $inner_services,
            "PROPERTY_medaspects" => $mediaspect_id,
        ));
        $filters["DOCTORS"] = "MEDIASPECT".$mediaspect_id."_DOCTORS_FILTER";
        $GLOBALS[$filters["DOCTORS"]] = $arFilter;

        $arFilter = array("ID" => $services_clinics);
        $filters["CLINICS"] = "MEDIASPECT".$mediaspect_id."_CLINICS_FILTER";
        $GLOBALS[$filters["CLINICS"]] = $arFilter;

        $arFilter = array("ID" => $inner_services);
        $filters["SERVICES"] = "MEDIASPECT".$mediaspect_id."_SERVICES_FILTER";
        $GLOBALS[$filters["SERVICES"]] = $arFilter;

        $arFilter = array("PROPERTY_MEDIASPECTS" => $mediaspect_id);
        $filters["PROGRAMS"] = "MEDIASPECT".$mediaspect_id."_PROGRAMS_FILTER";
        $GLOBALS[$filters["PROGRAMS"]] = $arFilter;

        return $filters;
    }

    public static function searchAll($query, $nocache = false)
    {
        global $USER;

        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_DOCTORS_ID, "ACTIVE"=>'Y', "NAME"=>"%".$query."%");
        $arSelectFields = Array("ID", "IBLOCK_ID", "NAME", "CODE", "DETAIL_PAGE_URL");
        $arDoctors = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields), $nocache);

        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_CLINICS_ID, "ACTIVE"=>'Y', "NAME"=>"%".$query."%");
        $arSelectFields = Array("ID", "IBLOCK_ID", "NAME", "CODE", "DETAIL_PAGE_URL");
        $arClinics = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields), $nocache);

        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_SERVICES_ID, "ACTIVE"=>'Y', "NAME"=>"%".$query."%");
        $arSelectFields = Array("IBLOCK_ID", "IBLOCK_SECTION_ID", "ID", "NAME", "CODE", "DETAIL_PAGE_URL");
        $arServices = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields, true), $nocache);

        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_SERVICES_ID, "GLOBAL_ACTIVE"=>'Y', "NAME"=>"%".$query."%", "DEPTH_LEVEL" => 2);
        $arSelectFields = Array("IBLOCK_ID", "ID", "NAME", "CODE", "SECTION_PAGE_URL");
        $arMediaspects = self::getValueFromCache("get_ib_sections_list", array($arFilter, $arSelectFields), $nocache);

        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_PROGRAMS_ID, "ACTIVE"=>'Y', "NAME"=>"%".$query."%");
        $arSelectFields = Array("IBLOCK_ID", "ID", "NAME", "CODE", "DETAIL_PAGE_URL");
        $arPrograms = self::getValueFromCache("get_ib_items_list", array($arFilter, $arSelectFields), $nocache);

        $results = array();
        self::appendSearchResults($results, $arDoctors);
        self::appendSearchResults($results, $arClinics);
        self::appendSearchResults($results, $arServices);
        self::appendSearchResults($results, $arMediaspects);
        self::appendSearchResults($results, $arPrograms);
        ksort($results);
        $results = array_values($results);

        return $results;
    }

    public static function sortItemsListCompare($el1, $el2) {
        if ($el1["SORT"]<$el2["SORT"]) return -1;
        if ($el1["SORT"]>$el2["SORT"]) return 1;
        if ($el1["NAME"]<$el2["NAME"]) return -1;
        if ($el1["NAME"]>$el2["NAME"]) return 1;
        if ($el1["ID"]<$el2["ID"]) return -1;
        if ($el1["ID"]>$el2["ID"]) return 1;
        return 0;
    }

    public static function sortItemsList(&$items) {
        return uasort($items, array('DrclinicsHelper', 'sortItemsListCompare'));
    }

}


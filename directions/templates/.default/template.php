<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$is_home_mode = isset($arParams['HOME_USE']) && $arParams['HOME_USE']=="Y" ? 1 : 0;
?>

<?$APPLICATION->IncludeComponent(
    "drclinic:services.collection",
    "",
    array(
        "AJAX_SEARCH" => "/ajax/search.php",
        "SEARCH_TEXT" => "Введите название услуги или анализа, специальность врача или ФИО (если знаете)"
    ),
    false,
    array('HIDE_ICONS' => 'Y')
);?>

<?if(!$is_home_mode):?>
<h1 class="cl-green mb-40"><?=$APPLICATION->ShowTitle()?></h1>
<?endif;?>
<div class="row">
	<div id="left_tab_menu" class="col-xs-12 col-md-3 left_tab_menu non-icons-menu">
		<ul class="nav nav-tabs tabs-left stylized middle-icon li-mb-10 fz18  napr-wrap pl-15" data-tabs="tabs">
			<?foreach($arResult['ITEMS'] as $arItem):?>
					<?if(isset($arParams["AJAX_MODE"]) && $arParams["AJAX_MODE"]=="Y"):?>
						<li data-base-href="?active_direction=<?=$arItem["ID"]?>" class="<?=($arItem["UF_ICO_CLASS"] ? $arItem["UF_ICO_CLASS"] : "")?> <?if($arItem["ACTIVE"]):?>active<?endif;?>">
							<a href="?active_direction=<?=$arItem["ID"]?>&active_tab=<?=$arResult["TAB"];?><?if($arResult["TAB"]):?><?endif;?>">
								<span class="napr-icon icon1"></span>
								<span class="napr-title d-bl mt-5"><?=$arItem["NAME"]?></span >
							</a>
						</li>
					<?else:?>
						<li class="<?=($arItem["UF_ICO_CLASS"] ? $arItem["UF_ICO_CLASS"] : "")?> <?if($arItem["ACTIVE"]):?>active<?endif;?>">
							<a href="/nashi-uslugi/med-uslugi/<?=$arItem["CODE"]?>/<?if($arResult["TAB"]):?>?active_tab=<?=$arResult["TAB"];?><?endif;?><?if($arResult["TAB"]):?><?endif;?>">
								<span class="napr-icon icon1"></span>
								<span class="napr-title d-bl mt-5"><?=$arItem["NAME"]?></span >
							</a>
						</li>
					<?endif;?>
			<?endforeach;?>
            <?if($arResult['VIEW_ALL_LINK']=="Y"):?>
                <li class="">
                    <a href="/nashi-uslugi/analysis/">
                        <span class="napr-icon icon1"></span>
                        <span class="napr-title d-bl mt-5">Лаборабория (анализы)</span >
                    </a>
                </li>
            <?endif;?>
		</ul>
		<?if($arResult['VIEW_ALL_LINK']=="Y"):?>
			<div class="mt-20">
				<a href="/nashi-uslugi/med-uslugi/" class=" pl-20 pr-20 pt-5 pb-5 border-grey-radius4px fz18">
					<span class="">Все направления <i class="fz18 fa fa-angle-right"></i></span>
				</a>
			</div>
		<?endif;?>
	</div>

	<div id="tabs" class="col-md-9 col-xs-12 tab-content" >
		<div role="tabpanel" class="tab-pane active" id="tab1">

			<div class="tabs-inner">
                <ul class="nav nav-tabs nav-justified green-arrow mb-30">
                    <?if(!$is_home_mode):?>
                        <li <?if(!$arResult["TAB"] || $arResult["TAB"]=='tab2-1'):?>class="active"<?endif;?> ><a data-url-replace="<?=!$is_home_mode;?>" data-toggle="tab" href="#tab2-1" class="nowrap ">Услуги/цены</a></li>
                        <li <?if($arResult["TAB"]=='tab2-2'):?>class="active"<?endif;?> ><a data-url-replace="<?=!$is_home_mode;?>" data-toggle="tab" href="#tab2-2" class="nowrap">Врачи</a></li>
                    <?else:?>
                        <li <?if(!$arResult["TAB"] || $arResult["TAB"]=='tab2-2'):?>class="active"<?endif;?> ><a data-url-replace="<?=!$is_home_mode;?>" data-toggle="tab" href="#tab2-2" class="nowrap">Врачи</a></li>
                        <li <?if($arResult["TAB"]=='tab2-1'):?>class="active"<?endif;?> ><a data-url-replace="<?=!$is_home_mode;?>" data-toggle="tab" href="#tab2-1" class="nowrap ">Услуги/цены</a></li>
                    <?endif;?>
                    <li <?if($arResult["TAB"]=='tab2-3'):?>class="active"<?endif;?>><a data-url-replace="<?=!$is_home_mode;?>" data-toggle="tab" href="#tab2-3" class="nowrap">Клиники</a></li>
                    <li <?if($arResult["TAB"]=='tab2-4'):?>class="active"<?endif;?>><a data-url-replace="<?=!$is_home_mode;?>" data-toggle="tab" href="#tab2-4" class="nowrap">О направлении</a></li>
                    <li <?if($arResult["TAB"]=='tab2-5'):?>class="active"<?endif;?>><a data-url-replace="<?=!$is_home_mode;?>" data-toggle="tab" href="#tab2-5" class="nowrap">Программы</a></li>
                </ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane <?if((!$arResult["TAB"] && !$is_home_mode) || $arResult["TAB"]=='tab2-1'):?>active<?endif;?>" id="tab2-1">
						<?$APPLICATION->IncludeComponent(
							"bitrix:catalog.section",
							"services_home",
							array(
								"IBLOCK_ID" => DrclinicsHelper::IBLOCK_SERVICES_ID,
								"ELEMENT_SORT_FIELD" => "SORT",
								"ELEMENT_SORT_ORDER" => "ASC",
								"ELEMENT_SORT_FIELD2" => "EXTERNAL_ID",
								"ELEMENT_SORT_ORDER2" => "ASC",
								"FILTER_NAME" => $arResult["FILTERS"]["SERVICES"],
								"PAGE_ELEMENT_COUNT" => $arResult['SERVICES_ITEMS_COUNT'],
								"LINE_ELEMENT_COUNT" => $arResult['SERVICES_ITEMS_COUNT'],
								"PROPERTY_CODE" => array("FOUNDATION", "STRUCTURE"),
								"PRICE_CODE" => array("DEF"),
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"SECTION_ID" => "",
								"SECTION_CODE" => "",
								"SECTION_USER_FIELDS" => array("UF_TITLE"),
								"INCLUDE_SUBSECTIONS" => "Y",
								"SHOW_ALL_WO_SECTION" => "Y",
								"META_KEYWORDS" => "",
								"META_DESCRIPTION" => "",
								"BROWSER_TITLE" => "",
								"ADD_SECTIONS_CHAIN" => "N",
								"SET_TITLE" => "N",
								"SET_STATUS_404" => "N",
								"CACHE_FILTER" => "N",
								"CACHE_GROUPS" => "N",
                                "DIRECTION_URL" => $arResult["SECTION_PAGE_URL"],
							),
							false,
							array('HIDE_ICONS' => 'Y')
						);?>
					</div>
					<div role="tabpanel" class="tab-pane <?if((!$arResult["TAB"] && $is_home_mode) || $arResult["TAB"]=='tab2-2'):?>active<?endif;?>" id="tab2-2">
						<?$APPLICATION->IncludeComponent(
							"bitrix:catalog.section",
							"doctors_home",
							array(
								"IBLOCK_ID" => DrclinicsHelper::IBLOCK_DOCTORS_ID,
								"ELEMENT_SORT_FIELD" => "SORT",
								"ELEMENT_SORT_ORDER" => "ASC",
								"ELEMENT_SORT_FIELD2" => "NAME",
								"ELEMENT_SORT_ORDER2" => "ASC",
								"FILTER_NAME" => $arResult["FILTERS"]["DOCTORS"],
								"PAGE_ELEMENT_COUNT" => $arResult['DOCTORS_ITEMS_COUNT'],
								"LINE_ELEMENT_COUNT" => $arResult['DOCTORS_ITEMS_COUNT'],
								"PROPERTY_CODE" => array("position", "FORUM_MESSAGE_CNT"),
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"SECTION_ID" => "",
								"SECTION_CODE" => "",
								"SECTION_USER_FIELDS" => array(),
								"INCLUDE_SUBSECTIONS" => "Y",
								"SHOW_ALL_WO_SECTION" => "Y",
								"META_KEYWORDS" => "",
								"META_DESCRIPTION" => "",
								"BROWSER_TITLE" => "",
								"ADD_SECTIONS_CHAIN" => "N",
								"SET_TITLE" => "N",
								"SET_STATUS_404" => "N",
								"CACHE_FILTER" => "N",
								"CACHE_GROUPS" => "N",
							),
							false,
							array('HIDE_ICONS' => 'Y')
						);?>
					</div>
					<div role="tabpanel" class="tab-pane <?if($arResult["TAB"]=='tab2-3'):?>active<?endif;?>" id="tab2-3">
						<?$APPLICATION->IncludeComponent(
							"bitrix:catalog.section",
							"clinics_home",
							array(
								"IBLOCK_ID" => DrclinicsHelper::IBLOCK_CLINICS_ID,
								"ELEMENT_SORT_FIELD" => "SORT",
								"ELEMENT_SORT_ORDER" => "ASC",
								"ELEMENT_SORT_FIELD2" => "NAME",
								"ELEMENT_SORT_ORDER2" => "ASC",
								"FILTER_NAME" => $arResult["FILTERS"]["CLINICS"],
								"PAGE_ELEMENT_COUNT" => $arResult['CLINICS_ITEMS_COUNT'],
								"LINE_ELEMENT_COUNT" => $arResult['CLINICS_ITEMS_COUNT'],
								"PROPERTY_CODE" => array("ADRESS", "PHONE"),
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"SECTION_ID" => "",
								"SECTION_CODE" => "",
								"SECTION_USER_FIELDS" => array(),
								"INCLUDE_SUBSECTIONS" => "Y",
								"SHOW_ALL_WO_SECTION" => "Y",
								"META_KEYWORDS" => "",
								"META_DESCRIPTION" => "",
								"BROWSER_TITLE" => "",
								"ADD_SECTIONS_CHAIN" => "N",
								"SET_TITLE" => "N",
								"SET_STATUS_404" => "N",
								"CACHE_FILTER" => "N",
								"CACHE_GROUPS" => "N",
                                "PAGER_SHOW_ALWAYS" => $is_home_mode ? "Y" : "N",
							),
							false,
							array('HIDE_ICONS' => 'Y')
						);?>
					</div>
					<div role="tabpanel" class="tab-pane <?if($arResult["TAB"]=='tab2-4'):?>active<?endif;?>" id="tab2-4">
						<h3 >О направлении</h3>
						<?=$arResult["DESCRIPTION"]?>
					</div>
					<div role="tabpanel" class="tab-pane <?if($arResult["TAB"]=='tab2-5'):?>active<?endif;?>" id="tab2-5">
						<?$APPLICATION->IncludeComponent(
							"bitrix:catalog.section",
							"programs_home",
							array(
								"IBLOCK_ID" => DrclinicsHelper::IBLOCK_PROGRAMS_ID,
								"ELEMENT_SORT_FIELD" => "SORT",
								"ELEMENT_SORT_ORDER" => "ASC",
								"ELEMENT_SORT_FIELD2" => "NAME",
								"ELEMENT_SORT_ORDER2" => "ASC",
								"FILTER_NAME" => $arResult["FILTERS"]["PROGRAMS"],
								"PAGE_ELEMENT_COUNT" => $arResult['PROGRAMS_ITEMS_COUNT'],
								"LINE_ELEMENT_COUNT" => $arResult['PROGRAMS_ITEMS_COUNT'],
								"PROPERTY_CODE" => array("FOUNDATION", "STRUCTURE"),
								"PRICE_CODE" => array("DEF"),
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"SECTION_ID" => "",
								"SECTION_CODE" => "",
								"SECTION_USER_FIELDS" => array(),
								"INCLUDE_SUBSECTIONS" => "Y",
								"SHOW_ALL_WO_SECTION" => "Y",
								"META_KEYWORDS" => "",
								"META_DESCRIPTION" => "",
								"BROWSER_TITLE" => "",
								"ADD_SECTIONS_CHAIN" => "N",
								"SET_TITLE" => "N",
								"SET_STATUS_404" => "N",
								"CACHE_FILTER" => "N",
								"CACHE_GROUPS" => "N",
								"ALL_PROGRAMS_ACTIVE" => "Y",
							),
							false,
							array('HIDE_ICONS' => 'Y')
						);?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div >

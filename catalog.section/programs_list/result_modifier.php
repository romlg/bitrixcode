<?

$arDescriptions = Array();
$arSectionTree = Array();

$rsTree = CIBlockSection::GetTreeList(array('IBLOCK_ID'=>$arParams['IBLOCK_ID'], 'ACTIVE'=>'Y'));
while ($arTree = $rsTree->GetNext()) {
	$arResult['SectionTree'][$arTree['ID']] = array(
		'ID' => $arTree['ID'],
		'NAME' => $arTree['NAME'],
		'SORT' => $arTree['SORT'],
		'DESCRIPTION' => $arTree['DESCRIPTION'],
		'IBLOCK_SECTION_ID' => $arTree['IBLOCK_SECTION_ID'],
		'DEPTH_LEVEL' => $arTree['DEPTH_LEVEL'],
		'SECTION_PAGE_URL' => $arTree['SECTION_PAGE_URL'],
		'ITEMS' => array(),
		'ITEMS_COUNT' => 0,
	);

    if(!empty($arTree['DESCRIPTION'])) {
        if(!is_set($arResult['descriptions'][$arTree['DESCRIPTION']]))
            $arResult['descriptions'][$arTree['DESCRIPTION']] = Array('title'=>$arTree['DESCRIPTION'], 'section_id' => Array($arTree['ID']));
        else
            $arResult['descriptions'][$arTree['DESCRIPTION']]['section_id'][] = $arTree['ID'];
    }
    else {
        $arResult['SectionTree'][$arTree['ID']]['DESCRIPTION'] = $arResult['SectionTree'][$arTree['IBLOCK_SECTION_ID']]['DESCRIPTION'];
    }
}

function IncItemsCount($id,&$tree) {
	if(empty($id)) return;
	$tree[$id]['ITEMS_COUNT'] ++;
	if (intval($tree[$id]['IBLOCK_SECTION_ID'])>0) {
		IncItemsCount($tree[$id]['IBLOCK_SECTION_ID'],$tree);
	}
}

if (!empty($arResult['ITEMS'])) {
	foreach ($arResult['ITEMS'] as $arItem) {
		$arResult['SectionTree'][$arItem['IBLOCK_SECTION_ID']]['ITEMS'][$arItem['ID']] =  array(
			'ID' => $arItem['ID'],
			'NAME' => $arItem['NAME'],
			'DETAIL_PAGE_URL' => $arItem['DETAIL_PAGE_URL'],
			'MIN_PRICE' => $arItem['MIN_PRICE'],
			'IBLOCK_SECTION_ID' => $arItem['IBLOCK_SECTION_ID'],
			'DETAIL_URL' => $arItem["DETAIL_PAGE_URL"],
            'DESCRIPTION' => $arResult['SectionTree'][$arItem['IBLOCK_SECTION_ID']]['DESCRIPTION'],
		);
		IncItemsCount($arItem['IBLOCK_SECTION_ID'],$arResult['SectionTree']);
	}
}
if (!empty($arResult['SectionTree'])) {
	foreach ($arResult['SectionTree'] as $id=>$arSection) {
		if ($arSection['ITEMS_COUNT'] == 0)
			unset($arResult['SectionTree'][$id]);
	}
}

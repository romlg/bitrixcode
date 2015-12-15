<?
$Item_with_img = Array();
$Item_no_img = Array();

foreach($arResult['ITEMS'] as $Item){
    if (!empty($Item['PREVIEW_PICTURE'])){
        $Item_with_img[] = $Item;
    }
    else {
        $Item_no_img[] = $Item;
    }
}

$newItems = array_merge( $Item_with_img, $Item_no_img );
$arResult['ITEMS'] = $newItems;
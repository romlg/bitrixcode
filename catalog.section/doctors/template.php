<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult['ITEMS'])):?>
    <div class="doctors mt-30">
		<div class="row mb-40">
        <?
        $count = 1;
		
        foreach ($arResult['ITEMS'] as $key => $arItem):
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
            $strMainID = $this->GetEditAreaId($arItem['ID']);

            $productTitle = (
            isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
                : $arItem['NAME']
            );
            $imgTitle = (
            isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
                : $arItem['NAME']
            );
            $nap_list = array();
            foreach ($arItem['PROPERTIES']['structure']['VALUE'] as $value){
                $res = CIBlockSection::GetByID($value);
                if($ar_res = $res->GetNext())
                    $nap_list[] =  $ar_res['NAME'];
            }
            

            $db_props = CIBlockElement::GetProperty(24, $arItem["ID"], array("sort" => "asc"), Array("CODE" => "FORUM_MESSAGE_CNT"));
            if ($ar_props = $db_props->Fetch()) {
                $FORUM_MESSAGE_CNT = IntVal($ar_props["VALUE"]);
            }
            else
                $FORUM_MESSAGE_CNT = false;
            ?>

                <div class="col-xs-6 col-md-3 one-doctor mb-40">
                    <div class="p-rel p-rel one-doc_img-wrap">
						<div class="visible-hover bg-orange-06 p-15 ">
							<a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="cl-white fz14 d-bl">
								<?
								$nap_list = array_unique($nap_list);
								foreach($nap_list as $key=>$nap_list_value):
									if ($key>0) {$nap_list_value = mb_strtolower($nap_list_value);}
									if ($key==count($nap_list)-1) {$str = '';}else{$str = ',';}
									?>
									<?=$nap_list_value;?><?=$str;?>
								<?endforeach?>
							</a>
						</div>
						<a id="<? echo $arItemIDs['PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $imgTitle; ?>">
							<img src="<? echo $arItem['PREVIEW_PICTURE']['SRC']?$arItem['PREVIEW_PICTURE']['SRC']:$this->GetFolder()."/images/no_photo.png" ?>" class="img-responsive one-doc_img"/>
						</a>
                    </div>
                    <h4 class="fz17 one-doc_title"><a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $productTitle; ?>"><? echo $productTitle; ?></a></h4>
                    
                    <button class="btn btn-bordered cl-orange" data-doctor_id="<?=$arItem["ID"]?>" data-toggle="modal" data-target="#doctorsModal">Записаться на приём</button>
                    
                </div>
				
        <?
			if ($count%4 == 0)  {
                echo('</div><div class="row mb-40">');
            }
        $count++;
        endforeach;?>
		</div>
    </div>
    <?
        if ($arParams["DISPLAY_BOTTOM_PAGER"])
        {
            ?><? echo $arResult["NAV_STRING"]; ?><?
        }
    ?>
<?else:?>
    <div class="not_found col-xs-12"><i class="fa fa-exclamation-circle"></i><span><?=GetMessage("CT_BCSE_NOT_FOUND");?></span></div>
<?endif;?>
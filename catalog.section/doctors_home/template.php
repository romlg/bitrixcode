<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult['ITEMS'])):?>
    <div class="doctors mt-30">
        <?if(!empty($arResult["NAV_STRING"])):?>
            <div class="pull-right">
                Показаны <?=count($arResult['ITEMS'])?> из <?=intval($arResult["NAV_RESULT"] ? $arResult["NAV_RESULT"]->NavRecordCount : 0);?>, &nbsp;
                <a href="/doctors/">Все врачи <i class="fa fa-angle-right"></i></a>
            </div>
        <?endif;?>
        <h3 class="mb-30 mt-40">Врачи</h3>
        <div class="row">
            <? $key=0; foreach ($arResult['ITEMS'] as $arItem):
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
                ?>
                <div class="col-xs-6 col-md-3 one-doctor">
                    <div class="p-rel">
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
                        <img src="<? echo $arItem['PREVIEW_PICTURE']['SRC']?$arItem['PREVIEW_PICTURE']['SRC']:$this->GetFolder()."/images/no_photo.png" ?>" class="img-responsive" >
                    </div>
                    <h4 class="fz17"><a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>"><? echo $productTitle; ?></a></h4><a data-scroll-services="doctor" data-scroll-doctor="<?=$arItem["ID"]?>" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>#form" data-doctor_id="<?=$arItem["ID"]?>" class="btn btn-bordered">Запись на прием</a>
                </div>
                <?if($key % 4 == 3):?>
                    </div><div class="row">
                <?endif; $key++;?>
            <?endforeach;?>

        </div>
    </div>
<?else:?>
    <h4 class="mb-30 mt-40">Врачи не найдены!</h4>
<?endif;?>

<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult["ITEMS"])):?>
    <?if(!empty($arResult["NAV_STRING"])):?>
        <div class="pull-right mb-20">
            Показаны <?=count($arResult['ITEMS'])?> из <?=intval($arResult["NAV_RESULT"] ? $arResult["NAV_RESULT"]->NavRecordCount : 0);?>, &nbsp;
            <a href="/clinics/">Все клиники <i class="fa fa-angle-right"></i></a>
        </div>
        <div class="clearfix"></div>
    <?endif;?>
    <div class="obj-list ">
        <?foreach($arResult["ITEMS"] as $key=>$arElement):?>
            <div class="obj-list_item b-rad-b-r-15 <?/*=$key==0?"active":""*/?>">
                <div class="clearfix mb-20">
                    <h4 class="mt-0 mb-10"><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?= $arElement["NAME"]?></a></h4>
                    <?if($arElement["PROPERTIES"]["ADRESS"]["VALUE"]):?>
                        <p class="fz13 mb-5">
                            <span class="text-muted">Адрес:	</span>
                            <?=$arElement["PROPERTIES"]["ADRESS"]["VALUE"];?>
                        </p>
                    <?endif;?>
                    <?if($arElement["PROPERTIES"]["PHONE"]["VALUE"]):?>
                        <p class="fz13 mb-5">
                            <span class="text-muted">Телефон:	</span>
                            <?=$arElement["PROPERTIES"]["PHONE"]["VALUE"];?>
                        </p>
                    <?endif;?>
                    <?if($arElement["PROPERTIES"]["METRO"]["VALUE"]):?>
                        <p class="fz13 mb-5">
                            <span class="text-muted">Метро:	</span>
                            <img width="15" src="<?=$this->GetFolder()?>/images/metro.png">&nbsp;
                            <?=$arElement["PROPERTIES"]["METRO"]["VALUE"];?>
                        </p>
                    <?endif;?>
                </div>
                <div class="p-abs-bottom-left mb-5 ml-25 ">
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="cl-green fz18">Подробнее <i class="fa fa-angle-right fz18"></i></a>
                </div>
                <div class="btn-bottom-right-container ">
                    <a data-scroll-services="doctor" data-scroll-clinic="<?=$arElement["ID"]?>" href="<?=$arElement["DETAIL_PAGE_URL"]?>#form" class="btn btn-bordered"><span>Записаться на прием</span></a>
                </div>
            </div>
        <?endforeach;?>
    </div>
<?else:?>
    <h4 class="mb-30 mt-40">Клиники не найдены!</h4>
<?endif;?>

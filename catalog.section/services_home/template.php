<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult['ITEMS'])):?>
<div class="prices">
    <?if(!empty($arResult["NAV_STRING"])):?>
        <div class="pull-right mb-20">
            Показаны <?=count($arResult['ITEMS'])?> из <?=intval($arResult["NAV_RESULT"] ? $arResult["NAV_RESULT"]->NavRecordCount : 0);?>, &nbsp;
            <a href="<?if($arParams["DIRECTION_URL"]):?><?=$arParams["DIRECTION_URL"];?><?else:?>/nashi-uslugi/med-uslugi/<?endif;?>">Все услуги <i class="fa fa-angle-right"></i></a>
        </div>
        <div class="clearfix"></div>
    <?endif;?>
    <div>
        <?foreach($arResult["SECTIONS"] as $arSection):?>
            <h4 class="cl-green mt-20"><?=$arSection["NAME"]?></h4>
            <ul class="list-unstyled last-no-border mb-0">
                <?foreach($arSection["ITEMS"] as $arItem):?>
                    <li class="border-b b-color-grey pt-10 pb-10 clearfix ">
                        <span class="row d-bl">
                            <span class="col-xs-12 col-md-7 mt-5">
                                <?=$arItem["NAME"]?>
                            </span>
                            <span class="col-xs-12 col-md-3 col-md-offset-2">
                                <?if($arItem["MIN_PRICE"]["VALUE"]):?><span class="cl-green fz25"><?=$arItem["MIN_PRICE"]["PRINT_VALUE_NOVAT"]?></span><br><?endif;?>
                                <a class="__direction_service" data-service-id="<?=$arItem["ID"]?>" data-service-name="<?=$arItem["NAME"]?>" data-service-price="<?=$arItem["MIN_PRICE"]["VALUE"]?>" href="<?=$arItem["DETAIL_PAGE_URL"]?>">Добавить услугу</a>
                            </span>
                        </span >
                    </li>
                <?endforeach;?>
            </ul>
        <?endforeach;?>
    </div>
</div>
<?else:?>
    <h4 class="mb-30 mt-40">Услуги не найдены!</h4>
<?endif;?>
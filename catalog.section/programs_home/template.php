<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult["SECTIONS"])):?>
    <div class="programs white-panel">
        <?if(!empty($arResult["NAV_STRING"])):?>
            <div class="pull-right mb-20">
                Показаны <?=count($arResult['ITEMS'])?> из <?=intval($arResult["NAV_RESULT"] ? $arResult["NAV_RESULT"]->NavRecordCount : 0);?>, &nbsp;
                <a href="/about/programs/">Все программы <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="clearfix"></div>
        <?endif;?>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?foreach($arResult["SECTIONS"] as $arSection):?>
                <?if($arSection["NAME"]):?>
                    <h3 class="mb-30 mt-40"><?=$arSection["NAME"]?></h3>
                <?endif;?>
                <?foreach($arSection["SECTIONS"] as $arSubSection):?>
                    <div class="panel panel-default ">
                        <div class="panel-heading heading-white" role="tab" id="heading<?=$arSubSection["ID"]?>">
                            <h4 class="panel-title fz21">
                                <a <?if($arParams["ALL_PROGRAMS_ACTIVE"]!="Y"):?>class="collapsed"<?endif;?> role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$arSubSection["ID"]?>" aria-expanded="true" aria-controls="collapse<?=$arSubSection["ID"]?>">
                                    <?=$arSubSection["NAME"]?>:
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?=$arSubSection["ID"]?>" class="panel-collapse collapse <?if($arParams["ALL_PROGRAMS_ACTIVE"]=="Y"):?>in<?endif;?>" role="tabpanel" aria-labelledby="heading<?=$arSubSection["ID"]?>">
                            <div class="panel-body">
                                <?if($arSubSection["DESCRIPTION"]):?>
                                    <?=$arSubSection["DESCRIPTION"];?>
                                <?endif;?>
                                <?if($arSubSection["ITEMS"]):?>
                                    <?if($arSubSection["DESCRIPTION"]):?><hr><?endif;?>
                                    <ul class="list-unstyled last-no-border mb-0">
                                        <?foreach($arSubSection["ITEMS"] as $arItem):?>
                                            <li class="border-b b-color-grey pt-10 pb-10 clearfix ">
                                                <span class="row d-bl">
                                                    <span class="col-xs-12 col-md-7 mt-5">
                                                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a >
                                                    </span>
                                                    <span class="col-xs-12 col-md-3 col-md-offset-2 text-right">
                                                        <?if($arItem["MIN_PRICE"]["VALUE"]):?><span class="cl-green fz20"><?=$arItem["MIN_PRICE"]["PRINT_VALUE_NOVAT"]?></span><br><?endif;?>
                                                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">Заказать программу</a>
                                                    </span>
                                                </span>
                                            </li>
                                        <?endforeach;?>
                                    </ul>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            <?endforeach;?>
        </div>
    </div>
<?elseif(!isset($arParams["ALL_IN_ONE_SECTION"]) || $arParams["ALL_IN_ONE_SECTION"]!='Y'):?>
    <h4 class="mb-30 mt-40">Программы не найдены!</h4>
<?endif;?>

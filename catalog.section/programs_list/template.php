<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="all-prices">
    <h5 id="resultsCount"></h5>
    <div class="services-content">
        <div id="sec_0" class="accordeon-body clearfix" data-level="1">
            <h4 class="accordeon-header" data-level="1" style="display: none;"><span class="servicesFound"></span></h4>
            <?$oldLevel = 0;?>
            <?if (empty($arResult["SectionTree"])):?>
            <?else:?>
                <?foreach($arResult["SectionTree"] as $arSection):
                    $arSection['DEPTH_LEVEL']++;
                    ?>
                    <?if ($arSection['DEPTH_LEVEL']<=$oldLevel):?>
                        <?=str_repeat('</div>',($oldLevel-$arSection['DEPTH_LEVEL']+1))?>
                    <?endif;?>
                    <div
                    id="collapse<?=$arSection['ID']?>"
                    class="<?=$arSection['DEPTH_LEVEL']==1?'accordeon-item':'accordeon-body'?> clearfix"
                    <?if ($arSection['DEPTH_LEVEL']==3):?> style="margin-left:<?=intval($arSection['DEPTH_LEVEL']*7)?>px" <?endif;?>
                    data-level="<?=$arSection['DEPTH_LEVEL'];?>">
                        <h4
                        class="accordeon-header <?if ($arSection['DEPTH_LEVEL']==2):?> cl-green fz16<?endif;?> <?if ($arSection['DEPTH_LEVEL']==3):?> cl-orange fz18<?endif;?>"
                        data-id="<?=$arSection['ID'];?>"
                        data-url="<?=$arSection['SECTION_PAGE_URL'];?>"
                        data-level="<?=$arSection['DEPTH_LEVEL'];?>">
                            <?=$arSection['NAME']?>
                            <span class="servicesFound"></span>
                        </h4>
                    <?if (!empty($arSection['ITEMS'])):?>
                        <ul class="service-picker-list unstyled accordeon-body">
                            <?foreach($arSection['ITEMS'] as $arItem):?>
                                <li class="item" id="sec_<?=$arSection['ID']?>_program_<?=$arItem['ID']?>">
                                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="service-list-link clearfix" data-id="<?=$arItem['ID']?>" data-section-id="<?=$arSection['ID']?>">
                                        <span class="service-name"><?=$arItem['NAME']?></span>
                                        <?if (!empty($arItem['MIN_PRICE'])):?>
                                            <?$price = $arItem['MIN_PRICE'];?>
                                            <?if ($price['VALUE']!=0):?>
                                                <span class="service-price"><?=$price['VALUE']?> <span class="rub">p</span></span>
                                            <?endif;?>
                                        <?endif?>
                                    </a>
                                </li>
                            <?endforeach;?>
                        </ul>
                    <?endif;?>
                    <?$oldLevel = $arSection['DEPTH_LEVEL']?>
                <?endforeach;?>
                </div>
            <?endif?>
        </div>
    </div>
</div> <!-- .all-programs -->

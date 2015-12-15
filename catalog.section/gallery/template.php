<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$sclides_count = count($arResult["ITEMS"]);
if ($sclides_count) {
?>
<div id="doctor_galery" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <!--<ol class="carousel-indicators">
        <?$sclides_count = count($arResult["ITEMS"]);?>
        <?for($i=0;$i<$sclides_count;$i++):?>
            <li data-target="#doctor_galery" data-slide-to="<?=$i?>" class="<?=$i==0?'active':''?>"</li>
        <?endfor?>
    </ol>-->

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <?foreach($arResult["ITEMS"] as $key=>$arElement):?>
            <div class="item <?=$key==0?"active":""?>">
                <a href="<?=$arElement["PROPERTIES"]["URL"]["VALUE"]?>">
                    <img src="<?= $arElement["PREVIEW_PICTURE"]["SRC"]?>" />
                    <div class="carousel-caption2">
                        <p class="logo_tx"><?= $arElement["NAME"]?></p>
                        <p class="annonce_tx"><?= $arElement["PREVIEW_TEXT"]?></p>
                    </div>
                </a>
            </div>
        <?endforeach;?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#doctor_galery" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#doctor_galery" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<? } ?>
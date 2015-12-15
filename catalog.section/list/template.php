<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div>
    <div class="clinics">
        <?foreach($arResult["ITEMS"] as $key=>$arElement):?>
            <div class="item <?=$key==0?"active":""?>">
                <h4><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?= $arElement["NAME"]?></a></h4>
                <?foreach($arElement["DISPLAY_PROPERTIES"] as $pid=>$arProperty):
                    echo '<span class="s_color">'.$arProperty["NAME"].':</span>&nbsp;';

                    if(is_array($arProperty["DISPLAY_VALUE"]))
                        echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
                    else
                        echo $arProperty["DISPLAY_VALUE"];
                    ?><br />
                <?endforeach?>
                <br><a class="more" href="<?=$arElement["DETAIL_PAGE_URL"]?>">Подробнее</a>
            </div>
        <?endforeach;?>
    </div>

</div>

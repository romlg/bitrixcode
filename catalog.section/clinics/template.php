<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<section class="pt-40 pb-20 content clinics-list">
    <div class="container">
        <?
        /* @todo: Брать из Инфоблока */
        $count = 1;
        $icons_mass_class = array(
            "Семейная медицина" => "napr7",
            "Кабинет охраны зрения" => "napr5",
            "Медосмотры" => "napr11",
            "Гастроскопия" => "napr9",
            "Стоматология" => "napr3",
            "Физиотерапия" => "napr8",
            "Рентген" => "napr4"
        );
        foreach($arResult["ITEMS"] as $key=>$arElement):
            if ($count == 1) {
                echo('<div class="row">');
            }
            ?>

            <div class="col-xs-12 col-md-6">
                <div class="obj-list_item b-rad-b-r-15 clinics-list_item">
                    <div class="clearfix mb-30">
                        <h3 class="mt-0 mb-20  fz21 clinics-list_item-title"><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?= $arElement["NAME"]?></a></h3>

                       <div class="clinics-list_item-addr">
						<?foreach($arElement["DISPLAY_PROPERTIES"] as $pid=>$arProperty):
                            echo '<p class="fz16 mb-5"><span class="text-muted">'.$arProperty["NAME"].':</span>&nbsp;';

                            if(is_array($arProperty["DISPLAY_VALUE"]))
                                echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
                            else {
                                if ($arProperty['CODE'] == 'METRO') {
                                    echo('<img src="' . $this->GetFolder() . '/images/metro.png" width="15"/>&nbsp;' . $arProperty["DISPLAY_VALUE"]);
                                }else{
                                    echo $arProperty["DISPLAY_VALUE"];
                                }
                            }
                            ?></p>
                        <?endforeach?>
						</div >
                        <div class="clinic_napr mt-30 clearfix">
                            <ul class=" list-inline fz16 no-border napr-wrap mt-0 mb-0 " data-tabs="tabs">
                                <? foreach ($arElement['PROPERTIES']['SPECIALIZATIONS']['VALUE'] as $key=>$value) {?>
                                    <li class="<?=$icons_mass_class[$value];?>">
                                        <span class="napr-icon icon1" title="<?=$value?>"></span>
                                    </li>
                                <? } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="p-abs-bottom-left mb-5 ml-25">
                        <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="cl-green fz18">Подробнее <i class="fa fa-angle-right fz18"></i></a>
                    </div>
                    <div class="btn-bottom-right-container ">
                        <button class="btn btn-bordered cl-orange" data-clinic_id="<?=$arElement["ID"]?>" data-toggle="modal" data-target="#clinicsModal"><span>Записаться на прием</span></button>
                    </div>
                </div>
            </div>
            <?
                if ($count == 2)  {
                    echo('</div>');
                    $count = 1;
                }else {
                    $count++;
                }
            endforeach;
            if ($count == 1) {
                echo('</div>');
            }
            ?>
    </div>
</section>
<div>
    <div class="clinics">

    </div>
</div>

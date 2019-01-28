{widget_mainsectors_data}
{if isset($mss) && $mss != null}
    <section id="mainsectors" class="clearfix">
        <div class="container section-block">
            <div class="vignette-list">
                <div class="row row-center">
                    {*{include file="home/loop/category.tpl" data=$categories classCol='vignette'}*}
                    {include file="mainsectors/loop/item.tpl" data=$mss classCol='vignette col-12 col-sm-6 col-md-4 col-xl-3'}
                </div>
            </div>
        </div>
    </section>
{/if}
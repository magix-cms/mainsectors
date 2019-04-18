{if isset($data.id)}
    {$data = [$data]}
{/if}
{if !$classCol}
    {$classCol = 'col-6 col-sm-6 col-md-4'}
{/if}
{if !isset($truncate)}
    {$truncate = 150}
{/if}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        {if isset($item.title)}{$item.name = $item.title}{/if}
        <div{if $classCol} class="{$classCol}"{/if}>
            <div class="figure">
                {if isset($item.img.name)}{$src = $item.img.medium.src}{else}{$src = $item.img.default}{/if}
                {strip}<picture>
                    {if isset($item.img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
                    <source type="image/webp" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src_webp']} {$item.img.medium['w']}w">
                    <source type="{$item.img.medium.ext}" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src']} {$item.img.medium['w']}w">
                    <!--[if IE 9]></video><![endif]-->{/if}
                    <img {if $lazy}data-{/if}src="{$src}" itemprop="image"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium['w']}" height="{$item.img.medium['h']}"{/if}alt="{$item.img.alt}" title="{$item.img.title}" class="img-responsive{if $lazy} lazyload{/if}" />
                    </picture>{/strip}
                <div class="desc{*{if $viewport === 'mobile'} sr-only{/if}*}">
                    <h2>{$item.name}</h2>
                    {if $item.resume}
                        <p>{$item.resume|truncate:$truncate:'...'}</p>
                    {elseif $item.content}
                        <p>{$item.content|strip_tags|truncate:$truncate:'...'}</p>
                    {/if}
                </div>
                <a class="all-hover" href="{$item.url}" title="{$item.name|ucfirst}">{$item.name}</a>
            </div>
        </div>
    {/foreach}
{/if}
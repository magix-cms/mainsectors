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
                {if count($item.img) > 1}
                    <img class="img-responsive lazyload" {*src="{$item.img.default|replace:'.png':'.jpg'}"*} data-src="{$item.img.medium.src}" alt="{$item.name}" title="{$item.name}" itemprop="image"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}/>
                {else}
                    <img class="img-responsive" src="{$item.img.default}" alt="{$item.name}" title="{$item.name}" />
                {/if}
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
{foreach $pages as $page}
    <li class="filter-item items" data-filter="{$page.name}" data-value="{$page.id}" data-id="{$page.id}">
        {$page.name|ucfirst}
        {if $page.child}
            <li class="optgroup">
                <ul class="list-unstyled">
                    {include file="loop/page.tpl" pages=$page.child}
                </ul>
            </li>
        {/if}
    </li>
{/foreach}
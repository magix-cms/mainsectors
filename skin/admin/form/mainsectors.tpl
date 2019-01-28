<div class="row">
    <form action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" class="col-ph-12 col-md-6 col-lg-4 validate_form add_to_ullist">
        <fieldset>
            <h2>{#ms_add_page#}</h2>
            <div class="form-group">
                <label for="type_ms">{#ms_type#}</label>
                <select name="type_ms" id="type_ms" class="form-control has-optional-fields">
                    <option value="">{#choose_page#}</option>
                    <option value="page" class="optional-field" data-target="#specific" data-get="pages" data-appendto="#pages">{#ms_pages#}</option>
                    <option value="category" class="optional-field" data-target="#specific" data-get="categories" data-appendto="#pages">{#ms_categories#}</option>
                </select>
                <div id="specific" class="additional-fields collapse">
                    <div class="form-group">
                        <div id="pages" class="btn-group btn-block selectpicker" data-clear="true" data-live="true">
                            <a href="#" class="clear"><span class="fa fa-times"></span><span class="sr-only">{#cancel_selection#}</span></a>
                            <button data-id="parent" type="button" class="btn btn-block btn-default dropdown-toggle">
                                <span class="placeholder">{#choose_page#}</span>
                                <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu">
                                <div class="live-filtering" data-clear="true" data-autocomplete="true" data-keys="true">
                                    <label class="sr-only" for="input-pages">{#search_in_list#}</label>
                                    <div class="search-box">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="search-pages">
                                                <span class="fa fa-search"></span>
                                                <a href="#" class="fa fa-times hide filter-clear"><span class="sr-only">{#clear_filter#}</span></a>
                                            </span>
                                            <input type="text" placeholder="Rechercher dans la liste" id="input-pages" class="form-control live-search" aria-describedby="search-pages" tabindex="1" />
                                        </div>
                                    </div>
                                    <div id="filter-pages" class="list-to-filter tree-display">
                                        <ul class="list-unstyled">
                                        </ul>
                                        <div class="no-search-results">
                                            <div class="alert alert-warning" role="alert"><i class="fa fa-warning margin-right-sm"></i>{#ms_no_entry_for#|sprintf:"<strong>'<span></span>'</strong>"}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="pages_id" id="pages_id" class="form-control mygroup" value="" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-main-theme" type="submit"><span class="fa fa-plus"></span> {#add#}</button>
            </div>
        </fieldset>
    </form>
    <div id="page-list" class="col-ph-12 col-md-6 col-lg-4">
        <h2>{#ms_on_homepage#}</h2>
        <ul id="table-page" class="list-group sortable" role="tablist">
            {foreach $mss as $ms}
                {include file="loop/pages.tpl"}
            {/foreach}
        </ul>
        <p class="no-entry alert alert-info{if {$mss|count}} hide{/if}">
            <span class="fa fa-info"></span> {#ms_no_products#}
        </p>
    </div>
</div>
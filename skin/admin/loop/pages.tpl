<li id="page_{$ms.id_ms}" class="panel list-group-item">
    <header>
        {$type = 'ms_'|cat:$ms.type_ms}
    <span class="fas fa-arrows-alt"></span> {$ms.name_ms} <sup>({#$type#})</sup>
    <div class="actions">
        <a href="#" class="btn btn-link action_on_record modal_action" data-id="{$ms.id_ms}" data-target="#delete_modal" data-controller="mainsectors" data-sub="page">
            <span class="fas fa-trash"></span>
        </a>
    </div>
    </header>
</li>
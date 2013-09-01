{* Purpose of this template: Display one certain label within an external context *}
<div id="label{$label.id}" class="muticketexternallabel">
{if $displayMode eq 'link'}
    <p class="muticketexternallink">
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='label' id=$label.id}" title="{$label.name|replace:"\"":""}">
    {$label.name|notifyfilters:'muticket.filter_hooks.labels.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUTicket::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="muticketexternaltitle">
            <strong>{$label.name|notifyfilters:'muticket.filter_hooks.labels.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="muticketexternalsnippet">
        &nbsp;
    </div>

    {* you can distinguish the context like this: *}
    {*if $source eq 'contentType'}
        ...
    {elseif $source eq 'scribite'}
        ...
    {/if*}

    {* you can enable more details about the item: *}
    {*
        <p class="muticketexternaldesc">
            {if $label.name ne ''}{$label.name}<br />{/if}
        </p>
    *}
{/if}
</div>

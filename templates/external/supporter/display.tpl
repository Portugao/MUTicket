{* Purpose of this template: Display one certain supporter within an external context *}
<div id="supporter{$supporter.id}" class="muticketexternalsupporter">
{if $displayMode eq 'link'}
    <p class="muticketexternallink">
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='supporter' id=$supporter.id}" title="{$supporter.username|replace:"\"":""}">
    {$supporter.username|notifyfilters:'muticket.filter_hooks.supporters.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUTicket::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="muticketexternaltitle">
            <strong>{$supporter.username|notifyfilters:'muticket.filter_hooks.supporters.filter'}</strong>
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
            {if $supporter.username ne ''}{$supporter.username}<br />{/if}
        </p>
    *}
{/if}
</div>

{* Purpose of this template: Display one certain rating within an external context *}
<div id="rating{$rating.id}" class="muticketexternalrating">
{if $displayMode eq 'link'}
    <p class="muticketexternallink">
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='rating' id=$rating.id}" title="{$rating.ratingvalue|replace:"\"":""}">
    {$rating.ratingvalue|notifyfilters:'muticket.filter_hooks.ratings.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUTicket::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="muticketexternaltitle">
            <strong>{$rating.ratingvalue|notifyfilters:'muticket.filter_hooks.ratings.filter'}</strong>
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
        </p>
    *}
{/if}
</div>

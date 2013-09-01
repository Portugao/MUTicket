{* Purpose of this template: Display one certain ticket within an external context *}
<div id="ticket{$ticket.id}" class="muticketexternalticket">
{if $displayMode eq 'link'}
    <p class="muticketexternallink">
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$ticket.id}" title="{$ticket.title|replace:"\"":""}">
    {$ticket.title|notifyfilters:'muticket.filter_hooks.tickets.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUTicket::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="muticketexternaltitle">
            <strong>{$ticket.title|notifyfilters:'muticket.filter_hooks.tickets.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="muticketexternalsnippet">
        {if $ticket.images ne ''}
          <a href="{$ticket.imagesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
          {if $ticket.imagesMeta.isImage}
              {thumb image=$ticket.imagesFullPath objectid="ticket-`$ticket.id`" preset=$ticketThumbPresetImages tag=true img_alt=$ticket.title}
          {else}
              {gt text='Download'} ({$ticket.imagesMeta.size|muticketGetFileSize:$ticket.imagesFullPath:false:false})
          {/if}
          </a>
        {else}&nbsp;{/if}
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
            {if $ticket.text ne ''}{$ticket.text}<br />{/if}
            {assignedcategorieslist categories=$ticket.categories doctrine2=true}
        </p>
    *}
{/if}
</div>

{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="ticket{$ticket.id}">
<dt>{$ticket.title|notifyfilters:'muticket.filter_hooks.tickets.filter'|htmlentities}</dt>
<dd>{if $ticket.images ne ''}
  <a href="{$ticket.imagesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
  {if $ticket.imagesMeta.isImage}
      {thumb image=$ticket.imagesFullPath objectid="ticket-`$ticket.id`" preset=$ticketThumbPresetImages tag=true img_alt=$ticket.title}
  {else}
      {gt text='Download'} ({$ticket.imagesMeta.size|muticketGetFileSize:$ticket.imagesFullPath:false:false})
  {/if}
  </a>
{else}&nbsp;{/if}
</dd>
{if $ticket.text ne ''}<dd>{$ticket.text}</dd>{/if}
<dd>{assignedcategorieslist categories=$ticket.categories doctrine2=true}</dd>
</dl>

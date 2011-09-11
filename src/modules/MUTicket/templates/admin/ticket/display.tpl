{* purpose of this template: tickets display view in admin area *}
{include file='admin/header.tpl'}
<div class="muticket-ticket muticket-display">
{gt text='Ticket' assign='templateTitle'}
{assign var='templateTitle' value=$ticket.title|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='display' size='small' __alt='Details'}
    <h3>{$templateTitle|notifyfilters:'muticket.filter_hooks.tickets.filter'}</h3>
</div>

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
<div class="muticketRightBox">
<h3>{gt text='Ratings'}</h3>

{if isset($ticket.rating) && $ticket.rating ne null}
    {include file='admin/rating/include_displayItemListMany.tpl' items=$ticket.rating}
{/if}

{checkpermission component='MUTicket::' instance='.*' level='ACCESS_ADMIN' assign='authAdmin'}
{if $authAdmin || (isset($uid) && isset($ticket.createdUserId) && $ticket.createdUserId eq $uid)}
<p class="manageLink">
    {gt text='Create rating' assign='createTitle'}
    <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='rating' ticket="`$ticket.id`" returnTo='adminDisplayTicket'}" title="{$createTitle}" class="z-icon-es-add">
        {$createTitle}
    </a>
</p>
{/if}
<h3>{gt text='Ticket'}</h3>

{if isset($ticket.parent) && $ticket.parent ne null}
    {include file='admin/ticket/include_displayItemListOne.tpl' item=$ticket.parent}
{/if}

{if !isset($ticket.parent) || $ticket.parent eq null}
{checkpermission component='MUTicket::' instance='.*' level='ACCESS_ADMIN' assign='authAdmin'}
{if $authAdmin || (isset($uid) && isset($ticket.createdUserId) && $ticket.createdUserId eq $uid)}
<p class="manageLink">
    {gt text='Create ticket' assign='createTitle'}
    <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='ticket' children="`$ticket.id`" returnTo='adminDisplayTicket'}" title="{$createTitle}" class="z-icon-es-add">
        {$createTitle}
    </a>
</p>
{/if}
{/if}
</div>
{/if}

<dl id="MUTicket_body">
    <dt>{gt text='Text'}</dt>
    <dd>{$ticket.text}</dd>
    <dt>{gt text='Parent_id'}</dt>
    <dd>{$ticket.parent_id}</dd>
    <dt>{gt text='Images'}</dt>
    <dd>{if $ticket.images ne ''}
  <a href="{$ticket.imagesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
  {if $ticket.imagesMeta.isImage}
      <img src="{$ticket.images|muticketImageThumb:$ticket.imagesFullPath:250:150}" width="250" height="150" alt="{$ticket.title|replace:"\"":""}" />
  {else}
      {gt text='Download'} ({$ticket.imagesMeta.size|muticketGetFileSize:$ticket.imagesFullPath:false:false})
  {/if}
  </a>
{else}&nbsp;{/if}
</dd>
    <dt>{gt text='Files'}</dt>
    <dd>{if $ticket.files ne ''}
  <a href="{$ticket.filesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.filesMeta.isImage} rel="imageviewer[ticket]"{/if}>
  {if $ticket.filesMeta.isImage}
      <img src="{$ticket.files|muticketImageThumb:$ticket.filesFullPath:250:150}" width="250" height="150" alt="{$ticket.title|replace:"\"":""}" />
  {else}
      {gt text='Download'} ({$ticket.filesMeta.size|muticketGetFileSize:$ticket.filesFullPath:false:false})
  {/if}
  </a>
{else}&nbsp;{/if}
</dd>
    <dt>{gt text='State'}</dt>
    <dd>{$ticket.state}</dd>
    <dt>{gt text='T_rating'}</dt>
    <dd>{$ticket.t_rating}</dd>
    <dt>{gt text='Rated'}</dt>
    <dd>{$ticket.rated}</dd>
    <dt>{gt text='Parent'}</dt>
    <dd>
    {if isset($ticket.Parent) && $ticket.Parent ne null}
      {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <a href="{modurl modname='MUTicket' type='admin' func='display' ot='ticket' id=$ticket.Parent.id}">
            {$ticket.Parent.title|default:""}
        </a>
        <a id="ticketItem{$ticket.Parent.id}Display" href="{modurl modname='MUTicket' type='admin' func='display' ot='ticket' id=$ticket.Parent.id theme='Printer'}" title="{gt text='Open quick view window'}" style="display: none">
            {icon type='view' size='extrasmall' __alt='Quick view'}
        </a>
        <script type="text/javascript" charset="utf-8">
        /* <![CDATA[ */
            document.observe('dom:loaded', function() {
                muticketInitInlineWindow($('ticketItem{{$ticket.Parent.id}}Display'), '{{$ticket.Parent.title|replace:"'":""}}');
            });
        /* ]]> */
        </script>
      {else}
        {$ticket.Parent.title|default:""}
      {/if}
    {else}
        {gt text='No set.'}
    {/if}
    </dd>
</dl>
    {include file='admin/include_categories_display.tpl' obj=$ticket}
    {include file='admin/include_standardfields_display.tpl' obj=$ticket}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
<p>
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_EDIT'}

        <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='ticket' id=$ticket.id}" title="{gt text='Edit'}" class="z-icon-es-edit">
            {gt text='Edit'}
        </a>
        <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='ticket' astemplate=$ticket.id}" title="{gt text='Reuse for new item'}" class="z-icon-es-saveas">
            {gt text='Reuse'}
        </a>
    {/checkpermissionblock}
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_DELETE'}
        <a href="{modurl modname='MUTicket' type='admin' func='delete' ot='ticket' id=$ticket.id}" title="{gt text='Delete'}" class="z-icon-es-delete">
            {gt text='Delete'}
        </a>
    {/checkpermissionblock}
    <a href="{modurl modname='MUTicket' type='admin' func='view' ot='ticket'}" title="{gt text='Back to overview'}" class="z-icon-es-back">
        {gt text='Back to overview'}
    </a>
</p>

{* include display hooks *}
{notifydisplayhooks eventname='muticket.ui_hooks.tickets.display_view' id=$ticket.id urlobject=$currentUrlObject assign='hooks'}
{foreach key='hookname' item='hook' from=$hooks}
    {$hook}
{/foreach}

<br style="clear: right" />
{/if}

</div>
{include file='admin/footer.tpl'}

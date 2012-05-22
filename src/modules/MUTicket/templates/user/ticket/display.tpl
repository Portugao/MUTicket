{* purpose of this template: tickets display view in user area *}
{include file='user/header.tpl'}
<div class="muticket-ticket muticket-display">
{gt text='Ticket' assign='templateTitle'}
{assign var='templateTitle' value=$ticket.title|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
{*Editing of template, 3 div container*}
    
<div class="ticket_user_body">
<div class="ticket_user_header"><h2>{gt text="Ticket"}: {$templateTitle|notifyfilters:'muticket.filter_hooks.tickets.filter'} {include file='user/include_categories_display.tpl' obj=$ticket}</h2><div class="ticket_user_header_menue">
</div></div>
<div class="ticket_user_body_left">
<div class="ticket_user_body_avatar">
{useravatar uid=$ticket.createdUserId}
</div>
<div class="ticket_user_body_standard">
{include file='user/include_standardfields_display.tpl' obj=$ticket}
</div>
<div class="ticket_user_body_rating">&nbsp;
</div>
</div>
<div class="ticket_user_body_right">
{*{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
<div class="muticketRightBox">
<h3>{gt text='Ratings'}</h3>

{if isset($ticket.rating) && $ticket.rating ne null}
    {include file='user/rating/include_displayItemListMany.tpl' items=$ticket.rating}
{/if}

{checkpermission component='MUTicket::' instance='.*' level='ACCESS_ADMIN'}
{if $authAdmin || (isset($uid) && isset($ticket.createdUserId) && $ticket.createdUserId eq $uid)}
<p class="manageLink">
    {gt text='Create rating' assign='createTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='edit' ot='rating' ticket="`$ticket.id`" returnTo='userDisplayTicket'}" title="{$createTitle}" class="z-icon-es-add">
        {$createTitle}
    </a>
</p>
{/if}
<h3>{gt text='Ticket'}</h3>

{if isset($ticket.parent) && $ticket.parent ne null}
    {include file='user/ticket/include_displayItemListOne.tpl' item=$ticket.parent}
{/if}

{if !isset($ticket.parent) || $ticket.parent eq null}
{checkpermission component='MUTicket::' instance='.*' level='ACCESS_ADMIN' assign='authAdmin'}
{if $authAdmin || (isset($uid) && isset($ticket.createdUserId) && $ticket.createdUserId eq $uid)}
<p class="manageLink">
    {gt text='Create ticket' assign='createTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='edit' ot='ticket' children="`$ticket.id`" returnTo='userDisplayTicket'}" title="{$createTitle}" class="z-icon-es-add">
        {$createTitle}
    </a>
</p>
{/if}
{/if}
</div>
{/if}*}
<div>
<h4>{gt text='Text'}</h4>
{$ticket.text}
<h4>{gt text='Image'}</h4>
{if $ticket.images ne ''}
  <a href="{$ticket.imagesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
  {if $ticket.imagesMeta.isImage}
      <img src="{$ticket.images|muticketImageThumb:$ticket.imagesFullPath:250:150}" width="250" height="150" alt="{$ticket.title|replace:"\"":""}" />
  {else}
      {gt text='Download'} ({$ticket.imagesMeta.size|muticketGetFileSize:$ticket.imagesFullPath:false:false})
  {/if}
  </a>
{else}
{gt text='No images uploaded!'}{/if}
<h4>{gt text='File'}</h4>
{if $ticket.files ne ''}
  <a href="{$ticket.filesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.filesMeta.isImage} rel="imageviewer[ticket]"{/if}>
  {if $ticket.filesMeta.isImage}
      <img src="{$ticket.files|muticketImageThumb:$ticket.filesFullPath:250:150}" width="250" height="150" alt="{$ticket.title|replace:"\"":""}" />
  {else}
      {gt text='Download'} ({$ticket.filesMeta.size|muticketGetFileSize:$ticket.filesFullPath:false:false})
  {/if}
  </a>
{else}{gt text='No files uploaded!'}{/if}

    {* <dt>{gt text='State'}</dt>
    <dd>{$ticket.state}</dd>
    <dt>{gt text='T_rating'}</dt>
    <dd>{$ticket.t_rating}</dd>
    <dt>{gt text='Rated'}</dt>
    <dd>{$ticket.rated}</dd>
    <dt>{gt text='Parent'}</dt>
    <dd>
    {if isset($ticket.Parent) && $ticket.Parent ne null}
      {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <a href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$ticket.Parent.id}">
            {$ticket.Parent.title|default:""}
        </a>
        <a id="ticketItem{$ticket.Parent.id}Display" href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$ticket.Parent.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" style="display: none">
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
    </dd> *}

<br style="clear: right" />

</div>
</div>
{* <div class="">
{if isset($ticket.parent) && $ticket.parent ne null}
    {include file='user/ticket/include_displayItemListOne.tpl' item=$ticket.parent}
{/if}
</div> *}
</div>

{foreach item='childTicket' from=$ticket.children}
{assign var='childrenid' value=$childTicket.id}
{pagesetvar name='children' value=$childrenid}
<div class="ticket_user_body">
<div class="ticket_user_body_left">
<div class="ticket_user_body_avatar">
{useravatar uid=$childTicket.createdUserId}
</div>
<div class="ticket_user_body_standard">
{include file='user/include_standardfields_display.tpl' obj=$childTicket}
</div>
<div class="ticket_user_body_rating">
{if $kind eq 1}

{if $pncore.user.uid ne $childTicket.createdUserId}
{if $childTicket.rated eq 0}
{modfunc modname='MUTicket' type='user' func='edit' ot='rating' ticket=$childTicket.id}
{/if}
{if $childTicket.rated eq 1}
{if isset($childTicket.rating) && $childTicket.rating ne null}
    {include file='user/rating/include_displayItemListMany.tpl' items=$childTicket.rating}
{/if}
{/if}
{/if}
{/if}
</div>
</div>
<div class="ticket_user_body_right">
{$childTicket.text}
    <h4>{gt text='Image'}</h4>
    {if $childTicket.images ne ''}
  <a href="{$childTicket.imagesFullPathURL}" title="{$childTicket.title|replace:"\"":""}"{if $childTicket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
  {if $childTicket.imagesMeta.isImage}
      <img src="{$childTicket.images|muticketImageThumb:$childTicket.imagesFullPath:250:150}" width="250" height="150" alt="{$childTicket.title|replace:"\"":""}" />
  {else}
      {gt text='Download'} ({$childTicket.imagesMeta.size|muticketGetFileSize:$childTicket.imagesFullPath:false:false})
  {/if}
    </a>
  {else}{gt text='No images uploaded!'}{/if}
  <h4>{gt text='File'}</h4>
{if $childTicket.files ne ''}
  <a href="{$childTicket.filesFullPathURL}" title="{$childTicket.title|replace:"\"":""}"{if $childTicket.filesMeta.isImage} rel="imageviewer[ticket]"{/if}>
  {if $childTicket.filesMeta.isImage}
      <img src="{$childticket.files|muticketImageThumb:$childticket.filesFullPath:250:150}" width="250" height="150" alt="{$childTicket.title|replace:"\"":""}" />
  {else}
      {gt text='Download'} ({$childticket.filesMeta.size|muticketGetFileSize:$childTicket.filesFullPath:false:false})
  {/if}
  </a>
{else}{gt text='No files uploaded!'}{/if}
</div>
</div> 
{/foreach}
{if $supporteractive eq 1}
{if $ticket.state eq 1}
<div id="ticket_inline_use">
{modfunc modname='MUTicket' type='user' func='edit' ot='ticket'}
</div>
{/if}
{else}
<div id="ticket_user_nosupport">
{gt text='Sorry! At the moment our support is not available!'}
</div>
{/if}
</div>
</div>
{if $func ne 'display'}}
{include file='user/footer.tpl'}
{/if}

{* purpose of this template: tickets display view in admin area *}
{include file='admin/header.tpl'}
<div class="muticket-ticket muticket-display withrightbox">
{gt text='Ticket' assign='templateTitle'}
{assign var='templateTitle' value=$ticket.title|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-admin-content-pagetitle">
    {icon type='display' size='small' __alt='Details'}
    <h3>{$templateTitle|notifyfilters:'muticket.filter_hooks.tickets.filter'}{icon id='itemactionstrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h3>
</div>

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    <div class="muticketrightbox">
        <h4>{gt text='Ticket'}</h4>
        
        {if isset($ticket.parent) && $ticket.parent ne null}
            {include file='admin/ticket/include_displayItemListOne.tpl' item=$ticket.parent}
        {/if}
        
        {if !isset($ticket.parent) || $ticket.parent eq null}
        {checkpermission component='MUTicket:Ticket:' instance="`$ticket.id`::" level='ACCESS_ADMIN' assign='authAdmin'}
        {if $authAdmin || (isset($uid) && isset($ticket.createdUserId) && $ticket.createdUserId eq $uid)}
        <p class="manageLink">
            {gt text='Create ticket' assign='createTitle'}
            <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='ticket' children="`$ticket.id`" returnTo='adminDisplayTicket'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
        </p>
        {/if}
        {/if}
        <h4>{gt text='Rating'}</h4>
        
        {if isset($ticket.rating) && $ticket.rating ne null}
            {include file='admin/rating/include_displayItemListOne.tpl' item=$ticket.rating}
        {/if}
        
        {if !isset($ticket.rating) || $ticket.rating eq null}
        {checkpermission component='MUTicket:Ticket:' instance="`$ticket.id`::" level='ACCESS_ADMIN' assign='authAdmin'}
        {if $authAdmin || (isset($uid) && isset($ticket.createdUserId) && $ticket.createdUserId eq $uid)}
        <p class="manageLink">
            {gt text='Create rating' assign='createTitle'}
            <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='rating' ticket="`$ticket.id`" returnTo='adminDisplayTicket'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
        </p>
        {/if}
        {/if}
        <h4>{gt text='Labels'}</h4>
        
        {if isset($ticket.labelticket) && $ticket.labelticket ne null}
            {include file='admin/label/include_displayItemListMany.tpl' items=$ticket.labelticket}
        {/if}
        
        {checkpermission component='MUTicket:Ticket:' instance="`$ticket.id`::" level='ACCESS_ADMIN' assign='authAdmin'}
        {if $authAdmin || (isset($uid) && isset($ticket.createdUserId) && $ticket.createdUserId eq $uid)}
        <p class="manageLink">
            {gt text='Create label' assign='createTitle'}
            <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='label' ticketlabel="`$ticket.id`" returnTo='adminDisplayTicket'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
        </p>
        {/if}
    </div>
{/if}

<dl>
    <dt>{gt text='Text'}</dt>
    <dd>{$ticket.text}</dd>
    <dt>{gt text='Parent_id'}</dt>
    <dd>{$ticket.parent_id}</dd>
    <dt>{gt text='Images'}</dt>
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
    <dt>{gt text='Files'}</dt>
    <dd>{if $ticket.files ne ''}
      <a href="{$ticket.filesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.filesMeta.isImage} rel="imageviewer[ticket]"{/if}>
      {if $ticket.filesMeta.isImage}
          {thumb image=$ticket.filesFullPath objectid="ticket-`$ticket.id`" preset=$ticketThumbPresetFiles tag=true img_alt=$ticket.title}
      {else}
          {gt text='Download'} ({$ticket.filesMeta.size|muticketGetFileSize:$ticket.filesFullPath:false:false})
      {/if}
      </a>
    {else}&nbsp;{/if}
    </dd>
    <dt>{gt text='State'}</dt>
    <dd>{$ticket.state|yesno:true}</dd>
    <dt>{gt text='Rated'}</dt>
    <dd>{$ticket.rated|yesno:true}</dd>
    <dt>{gt text='Owner'}</dt>
    <dd>{if $ticket.owner gt 0}
    {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        {$ticket.owner|profilelinkbyuid}
    {else}
      {usergetvar name='uname' uid=$ticket.owner}
    {/if}
    {else}&nbsp;{/if}
    </dd>
    <dt>{gt text='Due date'}</dt>
    <dd>{$ticket.dueDate|dateformat:'datetimebrief'}</dd>
    <dt>{gt text='Due text'}</dt>
    <dd>{$ticket.dueText}</dd>
    <dt>{gt text='Current state'}</dt>
    <dd>{$ticket.currentState|muticketGetListEntry:'ticket':'currentState'|safetext}</dd>
    <dt>{gt text='Parent'}</dt>
    <dd>
    {if isset($ticket.Parent) && $ticket.Parent ne null}
      {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
      <a href="{modurl modname='MUTicket' type='admin' func='display' ot='ticket' id=$ticket.Parent.id}">{strip}
        {$ticket.Parent.title|default:""}
      {/strip}</a>
      <a id="ticketItem{$ticket.Parent.id}Display" href="{modurl modname='MUTicket' type='admin' func='display' ot='ticket' id=$ticket.Parent.id theme='Printer'}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
      <script type="text/javascript">
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
        {gt text='Not set.'}
    {/if}
    </dd>
    
</dl>
{include file='admin/include_categories_display.tpl' obj=$ticket}
{include file='admin/include_standardfields_display.tpl' obj=$ticket}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    {* include display hooks *}
    {notifydisplayhooks eventname='muticket.ui_hooks.tickets.display_view' id=$ticket.id urlobject=$currentUrlObject assign='hooks'}
    {foreach key='providerArea' item='hook' from=$hooks}
        {$hook}
    {/foreach}
    {if count($ticket._actions) gt 0}
        <p id="itemactions">
        {foreach item='option' from=$ticket._actions}
            <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
        {/foreach}
        </p>
        <script type="text/javascript">
        /* <![CDATA[ */
            document.observe('dom:loaded', function() {
                muticketInitItemActions('ticket', 'display', 'itemactions');
            });
        /* ]]> */
        </script>
    {/if}
    <br style="clear: right" />
{/if}

</div>
{include file='admin/footer.tpl'}


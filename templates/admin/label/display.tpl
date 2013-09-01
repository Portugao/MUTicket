{* purpose of this template: labels display view in admin area *}
{include file='admin/header.tpl'}
<div class="muticket-label muticket-display withrightbox">
{gt text='Label' assign='templateTitle'}
{assign var='templateTitle' value=$label.name|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-admin-content-pagetitle">
    {icon type='display' size='small' __alt='Details'}
    <h3>{$templateTitle|notifyfilters:'muticket.filter_hooks.labels.filter'}{icon id='itemactionstrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h3>
</div>

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    <div class="muticketrightbox">
        <h4>{gt text='Tickets'}</h4>
        
        {if isset($label.ticketlabel) && $label.ticketlabel ne null}
            {include file='admin/ticket/include_displayItemListMany.tpl' items=$label.ticketlabel}
        {/if}
        
        {checkpermission component='MUTicket:Label:' instance="`$label.id`::" level='ACCESS_ADMIN' assign='authAdmin'}
        {if $authAdmin || (isset($uid) && isset($label.createdUserId) && $label.createdUserId eq $uid)}
        <p class="manageLink">
            {gt text='Create ticket' assign='createTitle'}
            <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='ticket' labelticket="`$label.id`" returnTo='adminDisplayLabel'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
        </p>
        {/if}
    </div>
{/if}

<dl>
    <dt>{gt text='Label color'}</dt>
    <dd>{$label.labelColor}</dd>
    
</dl>
{include file='admin/include_standardfields_display.tpl' obj=$label}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    {* include display hooks *}
    {notifydisplayhooks eventname='muticket.ui_hooks.labels.display_view' id=$label.id urlobject=$currentUrlObject assign='hooks'}
    {foreach key='providerArea' item='hook' from=$hooks}
        {$hook}
    {/foreach}
    {if count($label._actions) gt 0}
        <p id="itemactions">
        {foreach item='option' from=$label._actions}
            <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
        {/foreach}
        </p>
        <script type="text/javascript">
        /* <![CDATA[ */
            document.observe('dom:loaded', function() {
                muticketInitItemActions('label', 'display', 'itemactions');
            });
        /* ]]> */
        </script>
    {/if}
    <br style="clear: right" />
{/if}

</div>
{include file='admin/footer.tpl'}


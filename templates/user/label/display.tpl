{* purpose of this template: labels display view in user area *}
{include file='user/header.tpl'}
<div class="muticket-label muticket-display withrightbox">
{gt text='Label' assign='templateTitle'}
{assign var='templateTitle' value=$label.name|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-frontendcontainer">
    <h2>{$templateTitle|notifyfilters:'muticket.filter_hooks.labels.filter'}{icon id='itemactionstrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h2>

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    <div class="muticketrightbox">
        <h3>{gt text='Tickets'}</h3>
        
        {if isset($label.ticketlabel) && $label.ticketlabel ne null}
            {include file='user/ticket/include_displayItemListMany.tpl' items=$label.ticketlabel}
        {/if}
        
        {checkpermission component='MUTicket:Label:' instance="`$label.id`::" level='ACCESS_ADMIN' assign='authAdmin'}
        {if $authAdmin || (isset($uid) && isset($label.createdUserId) && $label.createdUserId eq $uid)}
        <p class="manageLink">
            {gt text='Create ticket' assign='createTitle'}
            <a href="{modurl modname='MUTicket' type='user' func='edit' ot='ticket' labelticket="`$label.id`" returnTo='userDisplayLabel'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
        </p>
        {/if}
    </div>
{/if}

<dl>
    <dt>{gt text='Label color'}</dt>
    <dd>{$label.labelColor}</dd>
    
</dl>
{include file='user/include_standardfields_display.tpl' obj=$label}

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
</div>
{include file='user/footer.tpl'}


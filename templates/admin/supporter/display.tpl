{* purpose of this template: supporters display view in admin area *}
{include file='admin/header.tpl'}
<div class="muticket-supporter muticket-display">
{gt text='Supporter' assign='templateTitle'}
{assign var='templateTitle' value=$supporter.username|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-admin-content-pagetitle">
    {icon type='display' size='small' __alt='Details'}
    <h3>{$templateTitle|notifyfilters:'muticket.filter_hooks.supporters.filter'}{icon id='itemactionstrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h3>
</div>


<dl>
    <dt>{gt text='Supportcats'}</dt>
    <dd>{$supporter.supportcats}</dd>
    <dt>{gt text='State'}</dt>
    <dd>{$supporter.state|yesno:true}</dd>
    
</dl>
{include file='admin/include_standardfields_display.tpl' obj=$supporter}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    {* include display hooks *}
    {notifydisplayhooks eventname='muticket.ui_hooks.supporters.display_view' id=$supporter.id urlobject=$currentUrlObject assign='hooks'}
    {foreach key='providerArea' item='hook' from=$hooks}
        {$hook}
    {/foreach}
    {if count($supporter._actions) gt 0}
        <p id="itemactions">
        {foreach item='option' from=$supporter._actions}
            <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
        {/foreach}
        </p>
        <script type="text/javascript">
        /* <![CDATA[ */
            document.observe('dom:loaded', function() {
                muticketInitItemActions('supporter', 'display', 'itemactions');
            });
        /* ]]> */
        </script>
    {/if}
{/if}

</div>
{include file='admin/footer.tpl'}


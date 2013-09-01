{* purpose of this template: ratings display view in admin area *}
{include file='admin/header.tpl'}
<div class="muticket-rating muticket-display">
{gt text='Rating' assign='templateTitle'}
{assign var='templateTitle' value=$rating.ratingvalue|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-admin-content-pagetitle">
    {icon type='display' size='small' __alt='Details'}
    <h3>{$templateTitle|notifyfilters:'muticket.filter_hooks.ratings.filter'}{icon id='itemactionstrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h3>
</div>


<dl>
    
</dl>
{include file='admin/include_standardfields_display.tpl' obj=$rating}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    {* include display hooks *}
    {notifydisplayhooks eventname='muticket.ui_hooks.ratings.display_view' id=$rating.id urlobject=$currentUrlObject assign='hooks'}
    {foreach key='providerArea' item='hook' from=$hooks}
        {$hook}
    {/foreach}
    {if count($rating._actions) gt 0}
        <p id="itemactions">
        {foreach item='option' from=$rating._actions}
            <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
        {/foreach}
        </p>
        <script type="text/javascript">
        /* <![CDATA[ */
            document.observe('dom:loaded', function() {
                muticketInitItemActions('rating', 'display', 'itemactions');
            });
        /* ]]> */
        </script>
    {/if}
{/if}

</div>
{include file='admin/footer.tpl'}


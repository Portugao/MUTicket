{* purpose of this template: ratings display view in user area *}
{include file='user/header.tpl'}
<div class="muticket-rating muticket-display">
{gt text='Rating' assign='templateTitle'}
{assign var='templateTitle' value=$rating.ratingvalue|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-frontendcontainer">
    <h2>{$templateTitle|notifyfilters:'muticket.filter_hooks.ratings.filter'}</h2>


<dl id="MUTicket_body">
</dl>
    {include file='user/include_standardfields_display.tpl' obj=$rating}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
{if count($rating._actions) gt 0}
    <p>{strip}
    {foreach item='option' from=$rating._actions}
        <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">
            {$option.linkText|safetext}
        </a>
    {/foreach}
    {/strip}</p>
{/if}

{* include display hooks *}
{notifydisplayhooks eventname='muticket.ui_hooks.ratings.display_view' id=$rating.id urlobject=$currentUrlObject assign='hooks'}
{foreach key='hookname' item='hook' from=$hooks}
    {$hook}
{/foreach}

{/if}

</div>
</div>
{include file='user/footer.tpl'}


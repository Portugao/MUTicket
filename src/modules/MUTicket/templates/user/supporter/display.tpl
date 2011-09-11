{* purpose of this template: supporters display view in user area *}
{include file='user/header.tpl'}
<div class="muticket-supporter muticket-display">
{gt text='Supporter' assign='templateTitle'}
{assign var='templateTitle' value=$supporter.username|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle|notifyfilters:'muticket.filter_hooks.supporters.filter'}</h2>


<dl id="MUTicket_body">
    <dt>{gt text='Supportcats'}</dt>
    <dd>{$supporter.supportcats}</dd>
</dl>
    {include file='user/include_standardfields_display.tpl' obj=$supporter}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
<p>
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_EDIT'}

        <a href="{modurl modname='MUTicket' type='user' func='edit' ot='supporter' id=$supporter.id}" title="{gt text='Edit'}" class="z-icon-es-edit">
            {gt text='Edit'}
        </a>
        <a href="{modurl modname='MUTicket' type='user' func='edit' ot='supporter' astemplate=$supporter.id}" title="{gt text='Reuse for new item'}" class="z-icon-es-saveas">
            {gt text='Reuse'}
        </a>
    {/checkpermissionblock}
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_DELETE'}
        <a href="{modurl modname='MUTicket' type='user' func='delete' ot='supporter' id=$supporter.id}" title="{gt text='Delete'}" class="z-icon-es-delete">
            {gt text='Delete'}
        </a>
    {/checkpermissionblock}
    <a href="{modurl modname='MUTicket' type='user' func='view' ot='supporter'}" title="{gt text='Back to overview'}" class="z-icon-es-back">
        {gt text='Back to overview'}
    </a>
</p>

{* include display hooks *}
{notifydisplayhooks eventname='muticket.ui_hooks.supporters.display_view' id=$supporter.id urlobject=$currentUrlObject assign='hooks'}
{foreach key='hookname' item='hook' from=$hooks}
    {$hook}
{/foreach}

{/if}

</div>
</div>
{include file='user/footer.tpl'}

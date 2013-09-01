{* Purpose of this template: Display supporters in html mailings *}
{*
<ul>
{foreach item='supporter' from=$items}
    <li>
        <a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$supporter.id fqurl=true}">{$supporter.username}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No supporters found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_supporter_display_description.tpl'}

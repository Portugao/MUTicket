{* Purpose of this template: Display supporters in html mailings *}
{*
<ul>
{foreach item='item' from=$items}
    <li>
        <a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id fqurl=true}">{$item.username}</a>
    </li>
{foreachelse}
    <li>{gt text='No supporters found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_Supporter_display_description.tpl'}

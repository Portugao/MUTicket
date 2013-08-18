{* Purpose of this template: Display tickets in html mailings *}
{*
<ul>
{foreach item='item' from=$items}
    <li>
        <a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id fqurl=true}">{$item.title}</a>
    </li>
{foreachelse}
    <li>{gt text='No tickets found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_Ticket_display_description.tpl'}

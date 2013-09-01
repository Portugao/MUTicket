{* Purpose of this template: Display tickets in html mailings *}
{*
<ul>
{foreach item='ticket' from=$items}
    <li>
        <a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$ticket.id fqurl=true}">{$ticket.title}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No tickets found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_ticket_display_description.tpl'}

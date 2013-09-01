{* Purpose of this template: Display tickets in text mailings *}
{foreach item='ticket' from=$items}
{$ticket.title}
{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$ticket.id fqurl=true}
-----
{foreachelse}
{gt text='No tickets found.'}
{/foreach}

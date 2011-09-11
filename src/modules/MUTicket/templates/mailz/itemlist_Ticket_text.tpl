{* Purpose of this template: Display tickets in text mailings *}
{foreach item='item' from=$items}
        {$item.title}
        {modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id fqurl=true}
-----
{foreachelse}
    {gt text='No tickets found.'}
{/foreach}

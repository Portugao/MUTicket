{* Purpose of this template: Display supporters in text mailings *}
{foreach item='item' from=$items}
        {$item.username}
        {modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id fqurl=true}
-----
{foreachelse}
    {gt text='No supporters found.'}
{/foreach}

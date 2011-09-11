{* Purpose of this template: Display supporters within an external context *}

{foreach item='item' from=$items}
    <h3>{$item.username}</h3>
    <p><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id}">{gt text='Read more'}</a></p>
{/foreach}

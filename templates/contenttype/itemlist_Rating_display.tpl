{* Purpose of this template: Display ratings within an external context *}

{foreach item='item' from=$items}
    <h3>{$item.ratingvalue}</h3>
    <p><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id}">{gt text='Read more'}</a></p>
{/foreach}

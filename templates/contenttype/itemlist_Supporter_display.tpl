{* Purpose of this template: Display supporters within an external context *}
{foreach item='supporter' from=$items}
    <h3>{$supporter.username}</h3>
    <p><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$supporter.id}">{gt text='Read more'}</a>
    </p>
{/foreach}

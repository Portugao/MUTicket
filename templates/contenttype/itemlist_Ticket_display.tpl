{* Purpose of this template: Display tickets within an external context *}
{foreach item='ticket' from=$items}
    <h3>{$ticket.title}</h3>
    <p><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$ticket.id}">{gt text='Read more'}</a>
    </p>
{/foreach}

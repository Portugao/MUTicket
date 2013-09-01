{* Purpose of this template: Display labels within an external context *}
{foreach item='label' from=$items}
    <h3>{$label.name}</h3>
    <p><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$label.id}">{gt text='Read more'}</a>
    </p>
{/foreach}

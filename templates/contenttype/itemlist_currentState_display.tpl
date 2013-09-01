{* Purpose of this template: Display current states within an external context *}
{foreach item='currentState' from=$items}
    <h3>{$currentState.title}</h3>
    <p><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$currentState.id}">{gt text='Read more'}</a>
    </p>
{/foreach}

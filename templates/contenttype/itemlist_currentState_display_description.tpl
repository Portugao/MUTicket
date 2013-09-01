{* Purpose of this template: Display current states within an external context *}
<dl>
    {foreach item='currentState' from=$items}
        <dt>{$currentState.title}</dt>
        {if $currentState.description}
            <dd>{$currentState.description|truncate:200:"..."}</dd>
        {/if}
        <dd><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$currentState.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>

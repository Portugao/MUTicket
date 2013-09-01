{* Purpose of this template: Display tickets within an external context *}
<dl>
    {foreach item='ticket' from=$items}
        <dt>{$ticket.title}</dt>
        {if $ticket.text}
            <dd>{$ticket.text|truncate:200:"..."}</dd>
        {/if}
        <dd><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$ticket.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>

{* Purpose of this template: Display supporters within an external context *}
<dl>
    {foreach item='supporter' from=$items}
        <dt>{$supporter.username}</dt>
        {if $supporter.supportcats}
            <dd>{$supporter.supportcats|truncate:200:"..."}</dd>
        {/if}
        <dd><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$supporter.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>

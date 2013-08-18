{* Purpose of this template: Display supporters within an external context *}

<dl>
{foreach item='item' from=$items}
    <dt>{$item.username}</dt>
{if $item.supportcats}
    <dd>{$item.supportcats|truncate:200:"..."}</dd>
{/if}
    <dd><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id}">{gt text='Read more'}</a></dd>
{foreachelse}
    <dt>{gt text='No entries found.'}</dt>
{/foreach}
</dl>

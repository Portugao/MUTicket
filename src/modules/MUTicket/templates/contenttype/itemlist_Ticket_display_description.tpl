{* Purpose of this template: Display tickets within an external context *}

<dl>
{foreach item='item' from=$items}
    <dt>{$item.title}</dt>
{if $item.text}
    <dd>{$item.text|truncate:200:"..."}</dd>
{/if}
    <dd><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id}">{gt text='Read more'}</a></dd>
{foreachelse}
    <dt>{gt text='No entries found.'}</dt>
{/foreach}
</dl>

{* Purpose of this template: Display labels within an external context *}
<dl>
    {foreach item='label' from=$items}
        <dt>{$label.name}</dt>
        {if $label.labelColor}
            <dd>{$label.labelColor|truncate:200:"..."}</dd>
        {/if}
        <dd><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$label.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>

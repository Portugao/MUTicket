{* Purpose of this template: Display ratings within an external context *}
<dl>
    {foreach item='rating' from=$items}
        <dt>{$rating.ratingvalue}</dt>
        <dd><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$rating.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>

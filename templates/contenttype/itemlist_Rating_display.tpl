{* Purpose of this template: Display ratings within an external context *}
{foreach item='rating' from=$items}
    <h3>{$rating.ratingvalue}</h3>
    <p><a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$rating.id}">{gt text='Read more'}</a>
    </p>
{/foreach}

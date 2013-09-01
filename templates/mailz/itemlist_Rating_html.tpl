{* Purpose of this template: Display ratings in html mailings *}
{*
<ul>
{foreach item='rating' from=$items}
    <li>
        <a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$rating.id fqurl=true}">{$rating.ratingvalue}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No ratings found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_rating_display_description.tpl'}

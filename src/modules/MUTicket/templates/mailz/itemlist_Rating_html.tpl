{* Purpose of this template: Display ratings in html mailings *}
{*
<ul>
{foreach item='item' from=$items}
    <li>
        <a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id fqurl=true}">{$item.ratingvalue}</a>
    </li>
{foreachelse}
    <li>{gt text='No ratings found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_Rating_display_description.tpl'}

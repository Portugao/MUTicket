{* Purpose of this template: Display ratings in text mailings *}
{foreach item='item' from=$items}
        {$item.ratingvalue}
        {modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$item.id fqurl=true}
-----
{foreachelse}
    {gt text='No ratings found.'}
{/foreach}

{* Purpose of this template: Display ratings in text mailings *}
{foreach item='rating' from=$items}
{$rating.ratingvalue}
{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$rating.id fqurl=true}
-----
{foreachelse}
{gt text='No ratings found.'}
{/foreach}

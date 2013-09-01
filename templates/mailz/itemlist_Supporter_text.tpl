{* Purpose of this template: Display supporters in text mailings *}
{foreach item='supporter' from=$items}
{$supporter.username}
{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$supporter.id fqurl=true}
-----
{foreachelse}
{gt text='No supporters found.'}
{/foreach}

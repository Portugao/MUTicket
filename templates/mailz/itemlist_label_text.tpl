{* Purpose of this template: Display labels in text mailings *}
{foreach item='label' from=$items}
{$label.name}
{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$label.id fqurl=true}
-----
{foreachelse}
{gt text='No labels found.'}
{/foreach}

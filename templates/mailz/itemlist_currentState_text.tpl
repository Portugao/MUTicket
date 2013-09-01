{* Purpose of this template: Display current states in text mailings *}
{foreach item='currentState' from=$items}
{$currentState.title}
{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$currentState.id fqurl=true}
-----
{foreachelse}
{gt text='No current states found.'}
{/foreach}

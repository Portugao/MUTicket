{* Purpose of this template: Display current states in html mailings *}
{*
<ul>
{foreach item='currentState' from=$items}
    <li>
        <a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$currentState.id fqurl=true}">{$currentState.title}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No current states found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_currentState_display_description.tpl'}

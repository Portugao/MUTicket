{* Purpose of this template: Display labels in html mailings *}
{*
<ul>
{foreach item='label' from=$items}
    <li>
        <a href="{modurl modname='MUTicket' type='user' func='display' ot=$objectType id=$label.id fqurl=true}">{$label.name}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No labels found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_label_display_description.tpl'}

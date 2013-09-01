{* purpose of this template: inclusion template for display of related current state in admin area *}
{icon type='delete' size='extrasmall' assign='removeImageArray'}
{assign var='removeImage' value="<img src=\"`$removeImageArray.src`\" width=\"16\" height=\"16\" alt=\"\" />"}

{if isset($item) && is_array($item) && !is_object($item[0])}
    {modapifunc modname='MUTicket' type='selection' func='getEntity' objectType='currentState' id=$item[0] assign='item'}
{/if}

<input type="hidden" id="{$idPrefix}ItemList" name="{$idPrefix}ItemList" value="{if isset($item) && (is_array($item) || is_object($item)) && isset($item.id)}{$item.id}{/if}" />
<input type="hidden" id="{$idPrefix}Mode" name="{$idPrefix}Mode" value="0" />

<ul id="{$idPrefix}ReferenceList">
{if isset($item) && (is_array($item) || is_object($item)) && isset($item.id)}
{assign var='idPrefixItem' value="`$idPrefix`Reference_`$item.id`"}
<li id="{$idPrefixItem}">
    {$item.title}
     <a id="{$idPrefixItem}Remove" href="javascript:muticketRemoveRelatedItem('{$idPrefix}', '{$item.id}');">{$removeImage}</a>
</li>
{/if}
</ul>

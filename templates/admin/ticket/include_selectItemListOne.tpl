{* purpose of this template: inclusion template for display of related Ticket in admin area *}
{icon type='delete' size='extrasmall' assign='removeImageArray'}
{assign var="removeImage" value="<img src=\"`$removeImageArray.src`\" width=\"16\" height=\"16\" alt=\"\" />"}

<input type="hidden" id="{$idPrefix}ItemList" name="{$idPrefix}ItemList" value="{if isset($item) && (is_array($item) || is_object($item)) && isset($item.id)}{$item.id}{/if}" />
<input type="hidden" id="{$idPrefix}Mode" name="{$idPrefix}Mode" value="0" />

<ul id="{$idPrefix}ReferenceList">
{if isset($item) && (is_array($item) || is_object($item)) && isset($item.id)}
{assign var='idPrefixItem' value="`$idPrefix`Reference_`$item.id`"}
    <li id="{$idPrefixItem}">
    {$item.title}
 <a id="{$idPrefixItem}Remove" href="javascript:muticketRemoveRelatedItem('{$idPrefix}', '{$item.id}');">{$removeImage}</a>
<br />
{if $item.images ne '' && isset($item.imagesFullPathURL)}
    <img src="{$item.imagesFullPathUrl|muticketImageThumb:$item.imagesFullPathUrl:50:40}" width="50" height="40" alt="{$item.title|replace:"\"":""}" />
{/if}
    </li>
{/if}
</ul>

{* purpose of this template: inclusion template for display of related labels in user area *}
{if !isset($nolink)}
    {assign var='nolink' value=false}
{/if}
{if isset($items) && $items ne null && count($items) gt 0}
{foreach name='relLoop' item='item' from=$items}
    <span style="font-size: 0.7em; color: {$item.fontColor}; background: {$item.labelColor}; padding: 2px; border-radius: 2px 4px;">{$item.name}</span>
{/foreach}

{/if}

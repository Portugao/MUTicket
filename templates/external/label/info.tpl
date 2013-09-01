{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="label{$label.id}">
<dt>{$label.name|notifyfilters:'muticket.filter_hooks.labels.filter'|htmlentities}</dt>
{if $label.name ne ''}<dd>{$label.name}</dd>{/if}
</dl>

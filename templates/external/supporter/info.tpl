{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="supporter{$supporter.id}">
<dt>{$supporter.username|notifyfilters:'muticket.filter_hooks.supporters.filter'|htmlentities}</dt>
{if $supporter.username ne ''}<dd>{$supporter.username}</dd>{/if}
</dl>

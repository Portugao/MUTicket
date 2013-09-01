{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="rating{$rating.id}">
<dt>{$rating.ratingvalue|notifyfilters:'muticket.filter_hooks.ratings.filter'|htmlentities}</dt>
</dl>

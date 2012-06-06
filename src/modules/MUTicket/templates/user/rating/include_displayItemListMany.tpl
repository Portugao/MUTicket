{* purpose of this template: inclusion template for display of related Ratings in user area *}

{if isset($items) && $items ne null}
<div class="relatedItemList Rating">
{gt text='You have rated this support answer:'}<br />
{foreach name='relLoop' item='item' from=$items}
        {section name=stern start=0 loop=$item.ratingvalue step=1}
        <img src="images/icons/small/favorites.png" />
        {/section}

   {* <script type="text/javascript" charset="utf-8">
    <![CDATA[
        document.observe('dom:loaded', function() {
            muticketInitInlineWindow($('ratingItem{{$item.id}}Display'), '{{$item.ratingvalue|replace:"'":""}}');
        });
     ]]>
    </script> *}
{/foreach}
</div>
{/if}


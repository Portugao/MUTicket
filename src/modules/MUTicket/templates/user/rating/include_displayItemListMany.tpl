{* purpose of this template: inclusion template for display of related Ratings in user area *}

{if isset($items) && $items ne null}
<div class="relatedItemList Rating">
{gt text='You have voted this support answer with:'}
{foreach name='relLoop' item='item' from=$items}

        <h2>{$item.ratingvalue} of 5</h2>

    <script type="text/javascript" charset="utf-8">
    <![CDATA[
        document.observe('dom:loaded', function() {
            muticketInitInlineWindow($('ratingItem{{$item.id}}Display'), '{{$item.ratingvalue|replace:"'":""}}');
        });
     ]]>
    </script>
{/foreach}
</div>
{/if}


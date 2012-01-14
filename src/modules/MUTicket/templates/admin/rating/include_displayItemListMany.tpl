{* purpose of this template: inclusion template for display of related Ratings in admin area *}

{if isset($items) && $items ne null}
<ul class="relatedItemList Rating">
{foreach name='relLoop' item='item' from=$items}
    <li>
   {* We don't need the link *}
   {* <a href="{modurl modname='MUTicket' type='admin' func='display' ot='rating' id=$item.id}"> *}
        {$item.ratingvalue}
   {* </a> *}
   {* We don't need the display of the rating 
    <a id="ratingItem{$item.id}Display" href="{modurl modname='MUTicket' type='admin' func='display' ot='rating' id=$item.id theme='Printer'}" title="{gt text='Open quick view window'}" style="display: none">
        {icon type='view' size='extrasmall' __alt='Quick view'}
    </a> *}
    <script type="text/javascript" charset="utf-8">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            muticketInitInlineWindow($('ratingItem{{$item.id}}Display'), '{{$item.ratingvalue|replace:"'":""}}');
        });
    /* ]]> */
    </script>

    </li>
{/foreach}
</ul>
{/if}


{* purpose of this template: inclusion template for display of related Ratings in admin area *}

<h4>
    <a href="{modurl modname='MUTicket' type='admin' func='display' ot='rating' id=$item.id}">
        {$item.ratingvalue}
    </a>
    <a id="ratingItem{$item.id}Display" href="{modurl modname='MUTicket' type='admin' func='display' ot='rating' id=$item.id theme='Printer'}" title="{gt text='Open quick view window'}" style="display: none">
        {icon type='view' size='extrasmall' __alt='Quick view'}
    </a>
</h4>
    <script type="text/javascript" charset="utf-8">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            muticketInitInlineWindow($('ratingItem{{$item.id}}Display'), '{{$item.ratingvalue|replace:"'":""}}');
        });
    /* ]]> */
    </script>


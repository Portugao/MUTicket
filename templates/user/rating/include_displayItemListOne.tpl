{* purpose of this template: inclusion template for display of related Ratings in user area *}

<h4>
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='rating' id=$item.id}">
        {$item.ratingvalue}
    </a>
</h4>
    <script type="text/javascript" charset="utf-8">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            muticketInitInlineWindow($('ratingItem{{$item.id}}Display'), '{{$item.ratingvalue|replace:"'":""}}');
        });
    /* ]]> */
    </script>


{* purpose of this template: inclusion template for display of related Supporters in user area *}

{if isset($items) && $items ne null}
<ul class="relatedItemList Supporter">
{foreach name='relLoop' item='item' from=$items}
    <li>
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='supporter' id=$item.id}">
        {$item.username}
    </a>
    <a id="supporterItem{$item.id}Display" href="{modurl modname='MUTicket' type='user' func='display' ot='supporter' id=$item.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" style="display: none">
        {icon type='view' size='extrasmall' __alt='Quick view'}
    </a>
    <script type="text/javascript" charset="utf-8">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            muticketInitInlineWindow($('supporterItem{{$item.id}}Display'), '{{$item.username|replace:"'":""}}');
        });
    /* ]]> */
    </script>

    </li>
{/foreach}
</ul>
{/if}


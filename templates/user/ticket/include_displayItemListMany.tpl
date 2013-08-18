{* purpose of this template: inclusion template for display of related Tickets in user area *}

{if isset($items) && $items ne null}
<ul class="relatedItemList Ticket">
{foreach name='relLoop' item='item' from=$items}
    <li>
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$item.id}">
        {$item.title}
    </a>
    <a id="ticketItem{$item.id}Display" href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$item.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" style="display: none">
        {icon type='view' size='extrasmall' __alt='Quick view'}
    </a>
    <script type="text/javascript" charset="utf-8">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            muticketInitInlineWindow($('ticketItem{{$item.id}}Display'), '{{$item.title|replace:"'":""}}');
        });
    /* ]]> */
    </script>
    <br />
{if $item.images ne '' && isset($item.imagesFullPathURL)}
    <img src="{$item.images|muticketImageThumb:$item.imagesFullPathURL:50:40}" width="50" height="40" alt="{$item.title|replace:"\"":""}" />
{/if}

    </li>
{/foreach}
</ul>
{/if}


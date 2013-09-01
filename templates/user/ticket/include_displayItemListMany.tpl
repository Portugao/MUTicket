{* purpose of this template: inclusion template for display of related tickets in user area *}
{if !isset($nolink)}
    {assign var='nolink' value=false}
{/if}
{if isset($items) && $items ne null && count($items) gt 0}
<ul class="relatedItemList ticket">
{foreach name='relLoop' item='item' from=$items}
    <li>
{strip}
{if !$nolink}
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$item.id}" title="{$item.title|replace:"\"":""}">
{/if}
{$item.title}
{if !$nolink}
    </a>
    <a id="ticketItem{$item.id}Display" href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$item.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
{/if}
{/strip}
{if !$nolink}
<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        muticketInitInlineWindow($('ticketItem{{$item.id}}Display'), '{{$item.title|replace:"'":""}}');
    });
/* ]]> */
</script>
{/if}
<br />
{if $item.images ne '' && isset($item.imagesFullPath) && $item.imagesMeta.isImage}
    {thumb image=$item.imagesFullPath objectid="ticket-`$item.id`" preset=$relationThumbPreset tag=true img_alt=$item.title}
{/if}
    </li>
{/foreach}
</ul>
{/if}

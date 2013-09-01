{* purpose of this template: inclusion template for display of related ratings in user area *}
{if !isset($nolink)}
    {assign var='nolink' value=false}
{/if}
<h4>
{strip}
{if !$nolink}
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='rating' id=$item.id}" title="{$item.ratingvalue|replace:"\"":""}">
{/if}
{$item.ratingvalue}
{if !$nolink}
    </a>
    <a id="ratingItem{$item.id}Display" href="{modurl modname='MUTicket' type='user' func='display' ot='rating' id=$item.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
{/if}
{/strip}
</h4>
{if !$nolink}
<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        muticketInitInlineWindow($('ratingItem{{$item.id}}Display'), '{{$item.ratingvalue|replace:"'":""}}');
    });
/* ]]> */
</script>
{/if}

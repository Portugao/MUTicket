{* purpose of this template: inclusion template for display of related supporters in admin area *}
{if !isset($nolink)}
    {assign var='nolink' value=false}
{/if}
<h4>
{strip}
{if !$nolink}
    <a href="{modurl modname='MUTicket' type='admin' func='display' ot='supporter' id=$item.id}" title="{$item.username|replace:"\"":""}">
{/if}
{$item.username}
{if !$nolink}
    </a>
    <a id="supporterItem{$item.id}Display" href="{modurl modname='MUTicket' type='admin' func='display' ot='supporter' id=$item.id theme='Printer'}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
{/if}
{/strip}
</h4>
{if !$nolink}
<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        muticketInitInlineWindow($('supporterItem{{$item.id}}Display'), '{{$item.username|replace:"'":""}}');
    });
/* ]]> */
</script>
{/if}

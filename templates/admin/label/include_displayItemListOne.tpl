{* purpose of this template: inclusion template for display of related labels in admin area *}
{if !isset($nolink)}
    {assign var='nolink' value=false}
{/if}
<h4>
{strip}
{if !$nolink}
    <a href="{modurl modname='MUTicket' type='admin' func='display' ot='label' id=$item.id}" title="{$item.name|replace:"\"":""}">
{/if}
{$item.name}
{if !$nolink}
    </a>
    <a id="labelItem{$item.id}Display" href="{modurl modname='MUTicket' type='admin' func='display' ot='label' id=$item.id theme='Printer'}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
{/if}
{/strip}
</h4>
{if !$nolink}
<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        muticketInitInlineWindow($('labelItem{{$item.id}}Display'), '{{$item.name|replace:"'":""}}');
    });
/* ]]> */
</script>
{/if}

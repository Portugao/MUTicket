{* purpose of this template: inclusion template for display of related current states in user area *}
{if !isset($nolink)}
    {assign var='nolink' value=false}
{/if}
<h4>
{strip}
{if !$nolink}
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='currentState' id=$item.id}" title="{$item.title|replace:"\"":""}">
{/if}
{$item.title}
{if !$nolink}
    </a>
    <a id="currentStateItem{$item.id}Display" href="{modurl modname='MUTicket' type='user' func='display' ot='currentState' id=$item.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
{/if}
{/strip}
</h4>
{if !$nolink}
<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        muticketInitInlineWindow($('currentStateItem{{$item.id}}Display'), '{{$item.title|replace:"'":""}}');
    });
/* ]]> */
</script>
{/if}
<br />
{if $item.uploadIcon ne '' && isset($item.uploadIconFullPath) && $item.uploadIconMeta.isImage}
    {thumb image=$item.uploadIconFullPath objectid="currentState-`$item.id`" preset=$relationThumbPreset tag=true img_alt=$item.title}
{/if}

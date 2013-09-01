{* purpose of this template: labels view view in user area *}
{include file='user/header.tpl'}
<div class="muticket-label muticket-view">
{gt text='Label list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>

{checkpermissionblock component='MUTicket:Label:' instance='::' level='ACCESS_EDIT'}
    {gt text='Create label' assign='createTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='edit' ot='label'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
{/checkpermissionblock}
{assign var='own' value=0}
{if isset($showOwnEntries) && $showOwnEntries eq 1}
    {assign var='own' value=1}
{/if}
{assign var='all' value=0}
{if isset($showAllEntries) && $showAllEntries eq 1}
    {gt text='Back to paginated view' assign='linkTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='view' ot='label'}" title="{$linkTitle}" class="z-icon-es-view">
        {$linkTitle}
    </a>
    {assign var='all' value=1}
{else}
    {gt text='Show all entries' assign='linkTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='view' ot='label' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
{/if}

{include file='user/label/view_quickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

<table class="z-datatable">
    <colgroup>
        <col id="cname" />
        <col id="clabelcolor" />
        <col id="citemactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hname" scope="col" class="z-left">
            {sortlink __linktext='Name' currentsort=$sort modname='MUTicket' type='user' func='view' ot='label' sort='name' sortdir=$sdir all=$all own=$own workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize}
        </th>
        <th id="hlabelcolor" scope="col" class="z-left">
            {sortlink __linktext='Label color' currentsort=$sort modname='MUTicket' type='user' func='view' ot='label' sort='labelColor' sortdir=$sdir all=$all own=$own workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize}
        </th>
        <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

{foreach item='label' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="hname" class="z-left">
            <a href="{modurl modname='MUTicket' type='user' func='display' ot='label' id=$label.id}" title="{gt text='View detail page'}">{$label.name|notifyfilters:'muticket.filterhook.labels'}</a>
        </td>
        <td headers="hlabelcolor" class="z-left">
            {$label.labelColor}
        </td>
        <td id="itemactions{$label.id}" headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($label._actions) gt 0}
                {foreach item='option' from=$label._actions}
                    <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                {/foreach}
                {icon id="itemactions`$label.id`trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                <script type="text/javascript">
                /* <![CDATA[ */
                    document.observe('dom:loaded', function() {
                        muticketInitItemActions('label', 'view', 'itemactions{{$label.id}}');
                    });
                /* ]]> */
                </script>
            {/if}
        </td>
    </tr>
{foreachelse}
    <tr class="z-datatableempty">
      <td class="z-left" colspan="3">
    {gt text='No labels found.'}
      </td>
    </tr>
{/foreach}

    </tbody>
</table>

{if !isset($showAllEntries) || $showAllEntries ne 1}
    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' modname='MUTicket' type='user' func='view' ot='label'}
{/if}


{notifydisplayhooks eventname='muticket.ui_hooks.labels.display_view' urlobject=$currentUrlObject assign='hooks'}
{foreach key='providerArea' item='hook' from=$hooks}
    {$hook}
{/foreach}
</div>
</div>
{include file='user/footer.tpl'}


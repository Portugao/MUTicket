{* purpose of this template: supporters view view in user area *}
{include file='user/header.tpl'}
<div class="muticket-supporter muticket-view">
{gt text='Supporter list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>

{checkpermissionblock component='MUTicket:Supporter:' instance='::' level='ACCESS_EDIT'}
    {gt text='Create supporter' assign='createTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='edit' ot='supporter'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
{/checkpermissionblock}
{assign var='own' value=0}
{if isset($showOwnEntries) && $showOwnEntries eq 1}
    {assign var='own' value=1}
{/if}
{assign var='all' value=0}
{if isset($showAllEntries) && $showAllEntries eq 1}
    {gt text='Back to paginated view' assign='linkTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='view' ot='supporter'}" title="{$linkTitle}" class="z-icon-es-view">
        {$linkTitle}
    </a>
    {assign var='all' value=1}
{else}
    {gt text='Show all entries' assign='linkTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='view' ot='supporter' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
{/if}

{include file='user/supporter/view_quickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

<table class="z-datatable">
    <colgroup>
        <col id="cusername" />
        <col id="csupportcats" />
        <col id="cstate" />
        <col id="citemactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="husername" scope="col" class="z-left">
            {sortlink __linktext='Username' currentsort=$sort modname='MUTicket' type='user' func='view' ot='supporter' sort='username' sortdir=$sdir all=$all own=$own workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize state=$state}
        </th>
        <th id="hsupportcats" scope="col" class="z-left">
            {sortlink __linktext='Supportcats' currentsort=$sort modname='MUTicket' type='user' func='view' ot='supporter' sort='supportcats' sortdir=$sdir all=$all own=$own workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize state=$state}
        </th>
        <th id="hstate" scope="col" class="z-center">
            {sortlink __linktext='State' currentsort=$sort modname='MUTicket' type='user' func='view' ot='supporter' sort='state' sortdir=$sdir all=$all own=$own workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize state=$state}
        </th>
        <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

{foreach item='supporter' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="husername" class="z-left">
            <a href="{modurl modname='MUTicket' type='user' func='display' ot='supporter' id=$supporter.id}" title="{gt text='View detail page'}">{$supporter.username|notifyfilters:'muticket.filterhook.supporters'}</a>
        </td>
        <td headers="hsupportcats" class="z-left">
            {$supporter.supportcats}
        </td>
        <td headers="hstate" class="z-center">
            {$supporter.state|yesno:true}
        </td>
        <td id="itemactions{$supporter.id}" headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($supporter._actions) gt 0}
                {foreach item='option' from=$supporter._actions}
                    <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                {/foreach}
                {icon id="itemactions`$supporter.id`trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                <script type="text/javascript">
                /* <![CDATA[ */
                    document.observe('dom:loaded', function() {
                        muticketInitItemActions('supporter', 'view', 'itemactions{{$supporter.id}}');
                    });
                /* ]]> */
                </script>
            {/if}
        </td>
    </tr>
{foreachelse}
    <tr class="z-datatableempty">
      <td class="z-left" colspan="4">
    {gt text='No supporters found.'}
      </td>
    </tr>
{/foreach}

    </tbody>
</table>

{if !isset($showAllEntries) || $showAllEntries ne 1}
    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' modname='MUTicket' type='user' func='view' ot='supporter'}
{/if}


{notifydisplayhooks eventname='muticket.ui_hooks.supporters.display_view' urlobject=$currentUrlObject assign='hooks'}
{foreach key='providerArea' item='hook' from=$hooks}
    {$hook}
{/foreach}
</div>
</div>
{include file='user/footer.tpl'}


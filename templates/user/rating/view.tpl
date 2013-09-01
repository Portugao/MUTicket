{* purpose of this template: ratings view view in user area *}
{include file='user/header.tpl'}
<div class="muticket-rating muticket-view">
{gt text='Rating list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>

{checkpermissionblock component='MUTicket:Rating:' instance='::' level='ACCESS_EDIT'}
    {gt text='Create rating' assign='createTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='edit' ot='rating'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
{/checkpermissionblock}
{assign var='own' value=0}
{if isset($showOwnEntries) && $showOwnEntries eq 1}
    {assign var='own' value=1}
{/if}
{assign var='all' value=0}
{if isset($showAllEntries) && $showAllEntries eq 1}
    {gt text='Back to paginated view' assign='linkTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='view' ot='rating'}" title="{$linkTitle}" class="z-icon-es-view">
        {$linkTitle}
    </a>
    {assign var='all' value=1}
{else}
    {gt text='Show all entries' assign='linkTitle'}
    <a href="{modurl modname='MUTicket' type='user' func='view' ot='rating' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
{/if}

{include file='user/rating/view_quickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

<table class="z-datatable">
    <colgroup>
        <col id="cratingvalue" />
        <col id="citemactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hratingvalue" scope="col" class="z-right">
            {sortlink __linktext='Ratingvalue' currentsort=$sort modname='MUTicket' type='user' func='view' ot='rating' sort='ratingvalue' sortdir=$sdir all=$all own=$own ticket=$ticket workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize}
        </th>
        <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

{foreach item='rating' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="hratingvalue" class="z-right">
            <a href="{modurl modname='MUTicket' type='user' func='display' ot='rating' id=$rating.id}" title="{gt text='View detail page'}">{$rating.ratingvalue|notifyfilters:'muticket.filterhook.ratings'}</a>
        </td>
        <td id="itemactions{$rating.id}" headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($rating._actions) gt 0}
                {foreach item='option' from=$rating._actions}
                    <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                {/foreach}
                {icon id="itemactions`$rating.id`trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                <script type="text/javascript">
                /* <![CDATA[ */
                    document.observe('dom:loaded', function() {
                        muticketInitItemActions('rating', 'view', 'itemactions{{$rating.id}}');
                    });
                /* ]]> */
                </script>
            {/if}
        </td>
    </tr>
{foreachelse}
    <tr class="z-datatableempty">
      <td class="z-left" colspan="2">
    {gt text='No ratings found.'}
      </td>
    </tr>
{/foreach}

    </tbody>
</table>

{if !isset($showAllEntries) || $showAllEntries ne 1}
    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' modname='MUTicket' type='user' func='view' ot='rating'}
{/if}


{notifydisplayhooks eventname='muticket.ui_hooks.ratings.display_view' urlobject=$currentUrlObject assign='hooks'}
{foreach key='providerArea' item='hook' from=$hooks}
    {$hook}
{/foreach}
</div>
</div>
{include file='user/footer.tpl'}


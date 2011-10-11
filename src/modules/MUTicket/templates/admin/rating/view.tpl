{* purpose of this template: ratings view view in admin area *}
<div class="muticket-rating muticket-view">
{include file='admin/header.tpl'}
{gt text='Rating list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='view' size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>


    {checkpermissionblock component='MUTicket::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create rating' assign='createTitle'}
        <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='rating'}" title="{$createTitle}" class="z-icon-es-add">
            {$createTitle}
        </a>
    {/checkpermissionblock}

    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='MUTicket' type='admin' func='view' ot='rating'}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='MUTicket' type='admin' func='view' ot='rating' all=1}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
    {/if}

<table class="z-datatable">
    <colgroup>
        <col id="cratingvalue" />
        <col id="cintactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hratingvalue" scope="col" align="right" valign="middle">
            {sortlink __linktext='Ratingvalue' sort='ratingvalue' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='rating'}
        </th>
        <th id="hintactions" scope="col" align="left" valign="middle" class="z-wrap z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='rating' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="hratingvalue" align="right" valign="top">
            {$rating.ratingvalue|notifyfilters:'muticket.filterhook.ratings'}
        </td>
        <td headers="hintactions" align="left" valign="top" style="white-space: nowrap">
            <a href="{modurl modname='MUTicket' type='admin' func='display' ot='rating' id=$rating.id}" title="{$rating.ratingvalue|replace:"\"":""}">
                {icon type='display' size='extrasmall' __alt='Details'}
            </a>
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_EDIT'}
            <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='rating' id=$rating.id}" title="{gt text='Edit'}">
                {icon type='edit' size='extrasmall' __alt='Edit'}
            </a>
            <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='rating' astemplate=$rating.id}" title="{gt text='Reuse for new item'}">
                {icon type='saveas' size='extrasmall' __alt='Reuse'}
            </a>
    {/checkpermissionblock}
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_DELETE'}
            <a href="{modurl modname='MUTicket' type='admin' func='delete' ot='rating' id=$rating.id}" title="{gt text='Delete'}">
                {icon type='delete' size='extrasmall' __alt='Delete'}
            </a>
    {/checkpermissionblock}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-admintableempty">
          <td align="left" valign="top" colspan="2">
            {gt text='No ratings found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
    {/if}
</div>
{include file='admin/footer.tpl'}


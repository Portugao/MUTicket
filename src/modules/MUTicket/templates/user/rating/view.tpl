{* purpose of this template: ratings view view in user area *}
<div class="muticket-rating muticket-view">
{include file='user/header.tpl'}
{gt text='Rating list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>


    {checkpermissionblock component='MUTicket::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create rating' assign='createTitle'}
        <a href="{modurl modname='MUTicket' type='user' func='edit' ot='rating'}" title="{$createTitle}" class="z-icon-es-add">
            {$createTitle}
        </a>
    {/checkpermissionblock}


<table class="z-datatable">
    <colgroup>
        <col id="cratingvalue" />
        <col id="cintactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hratingvalue" scope="col" align="right" valign="middle">
            {sortlink __linktext='Ratingvalue' sort='ratingvalue' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='rating'}
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
            <a href="{modurl modname='MUTicket' type='user' func='display' ot='rating' id=$rating.id}" title="{$rating.ratingvalue|replace:"\"":""}">
                {icon type='display' size='extrasmall' __alt='Details'}
            </a>
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_EDIT'}
            <a href="{modurl modname='MUTicket' type='user' func='edit' ot='rating' id=$rating.id}" title="{gt text='Edit'}">
                {icon type='edit' size='extrasmall' __alt='Edit'}
            </a>
            <a href="{modurl modname='MUTicket' type='user' func='edit' ot='rating' astemplate=$rating.id}" title="{gt text='Reuse for new item'}">
                {icon type='saveas' size='extrasmall' __alt='Reuse'}
            </a>
    {/checkpermissionblock}
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_DELETE'}
            <a href="{modurl modname='MUTicket' type='user' func='delete' ot='rating' id=$rating.id}" title="{gt text='Delete'}">
                {icon type='delete' size='extrasmall' __alt='Delete'}
            </a>
    {/checkpermissionblock}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-datatableempty">
          <td align="left" valign="top" colspan="2">
            {gt text='No ratings found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}

    {notifydisplayhooks eventname='muticket.ui_hooks.ratings.display_view' urlobject=$currentUrlObject assign='hooks'}
    {foreach key='hookname' item='hook' from=$hooks}
        {$hook}
    {/foreach}
</div>
</div>
{include file='user/footer.tpl'}

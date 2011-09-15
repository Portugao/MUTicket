{* purpose of this template: supporters view view in user area *}
<div class="muticket-supporter muticket-view">
{include file='user/header.tpl'}
{gt text='Supporter list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>


    {checkpermissionblock component='MUTicket::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create supporter' assign='createTitle'}
        <a href="{modurl modname='MUTicket' type='user' func='edit' ot='supporter'}" title="{$createTitle}" class="z-icon-es-add">
            {$createTitle}
        </a>
    {/checkpermissionblock}


<table class="z-datatable">
    <colgroup>
        <col id="cusername" />
        <col id="csupportcats" />
        <col id="cstate" />
        <col id="cintactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="husername" scope="col" align="left" valign="middle">
            {sortlink __linktext='Username' sort='username' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='supporter'}
        </th>
        <th id="hsupportcats" scope="col" align="left" valign="middle">
            {sortlink __linktext='Supportcats' sort='supportcats' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='supporter'}
        </th>
        <th id="hstate" scope="col" align="center" valign="middle">
            {sortlink __linktext='State' sort='state' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='supporter'}
        </th>
        <th id="hintactions" scope="col" align="left" valign="middle" class="z-wrap z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='supporter' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="husername" align="left" valign="top">
            {$supporter.username|notifyfilters:'muticket.filterhook.supporters'}
        </td>
        <td headers="hsupportcats" align="left" valign="top">
            {$supporter.supportcats}
        </td>
        <td headers="hstate" align="center" valign="top">
            {$supporter.state|yesno:true}
        </td>
        <td headers="hintactions" align="left" valign="top" style="white-space: nowrap">
            <a href="{modurl modname='MUTicket' type='user' func='display' ot='supporter' id=$supporter.id}" title="{$supporter.username|replace:"\"":""}">
                {icon type='display' size='extrasmall' __alt='Details'}
            </a>
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_EDIT'}
            <a href="{modurl modname='MUTicket' type='user' func='edit' ot='supporter' id=$supporter.id}" title="{gt text='Edit'}">
                {icon type='edit' size='extrasmall' __alt='Edit'}
            </a>
            <a href="{modurl modname='MUTicket' type='user' func='edit' ot='supporter' astemplate=$supporter.id}" title="{gt text='Reuse for new item'}">
                {icon type='saveas' size='extrasmall' __alt='Reuse'}
            </a>
    {/checkpermissionblock}
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_DELETE'}
            <a href="{modurl modname='MUTicket' type='user' func='delete' ot='supporter' id=$supporter.id}" title="{gt text='Delete'}">
                {icon type='delete' size='extrasmall' __alt='Delete'}
            </a>
    {/checkpermissionblock}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-datatableempty">
          <td align="left" valign="top" colspan="4">
            {gt text='No supporters found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}

    {notifydisplayhooks eventname='muticket.ui_hooks.supporters.display_view' urlobject=$currentUrlObject assign='hooks'}
    {foreach key='hookname' item='hook' from=$hooks}
        {$hook}
    {/foreach}
</div>
</div>
{include file='user/footer.tpl'}

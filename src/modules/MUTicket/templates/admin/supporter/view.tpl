{* purpose of this template: supporters view view in admin area *}
<div class="muticket-supporter muticket-view">
{include file='admin/header.tpl'}
{gt text='Supporter list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='view' size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>


    {checkpermissionblock component='MUTicket::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create supporter' assign='createTitle'}
        <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='supporter'}" title="{$createTitle}" class="z-icon-es-add">
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
            {sortlink __linktext='Username' sort='username' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='supporter'}
        </th>
        <th id="hsupportcats" scope="col" align="left" valign="middle">
            {sortlink __linktext='Supportcats' sort='supportcats' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='supporter'}
        </th>
        <th id="hstate" scope="col" align="center" valign="middle">
            {sortlink __linktext='Present?' sort='state' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='supporter'}
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
        {if $supporter.supportcats}
        <ul>
        {foreach item='cat' from=$supporter.supportcats}
            <li>{$cat|muticketCatName:$cat}</li>
        {/foreach}
        </ul>
        {/if}
        </td>
        <td headers="hstate" align="center" valign="top">
            {$supporter.state|yesno:true}
        </td>
        <td headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($supporter._actions) gt 0}
            {strip}
                {foreach item='option' from=$supporter._actions}
                    <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>
                        {icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}
                    </a>
                {/foreach}
            {/strip}
            {/if}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-admintableempty">
          <td align="left" valign="top" colspan="4">
            {gt text='No supporters found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
</div>
{include file='admin/footer.tpl'}

{* purpose of this template: tickets view view in user area *}
<div class="muticket-ticket muticket-view">
{include file='user/header.tpl'}
{if $kind eq 0}
<div class="z-informationmsg z-formnote"><div style="line-height: 1.2em; padding: 0 10px;"><img src='modules/MUTicket/images/New.png' /> {gt text='New ticket'} <img src='modules/MUTicket/images/Supporter.png' /> {gt text='Waiting for supporter feedback'} <img src='modules/MUTicket/images/Customer.png' /> {gt text='Waiting for customer feedback'}</div></div>
{/if}
{if $supporteractive eq 0}
<div id="ticket_user_nosupport">
{gt text='Sorry. At the moment our support is not available!'}
</div>
{/if}
{if $state eq 2 || $state eq 3}
{if $state eq 2}
{gt text='Open Tickets Overview' assign='templateTitle'}
{/if}
{if $state eq 3}
{gt text='Closed Tickets Overview' assign='templateTitle'}
{/if}
{else}
{gt text='Ticket Overview' assign='templateTitle'}
{/if}

{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <div class="ticket_user_header"><h2>{$templateTitle}</h2><div class="ticket_user_header_menue"></div></div>


   {* {checkpermissionblock component='MUTicket::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create ticket' assign='createTitle'}
        <a href="{modurl modname='MUTicket' type='user' func='edit' ot='ticket'}" title="{$createTitle}" class="z-icon-es-add">
            {$createTitle}
        </a>
    {/checkpermissionblock} *}


<table class="z-datatable ticket_user_table">
    <colgroup>
        {if $kind eq 0 && $state ne 0}
        <col id="cstate" />
        {/if}
        <col id="ccreated" />
        <col id="ctitle" />
        <col id="copen" />
        <col id="cintactions" />
    </colgroup>
    <thead>
    <tr>
        {if $kind eq 0 && $state ne 0}
        <th id="hstate" scope="col" align="center" valign="middle">
        {gt text='State'}
        </th>
        {/if}
        <th id="hstate" scope="col" align="left" valign="middle">
        {if $state}
            {if $state == 2}
                {sortlink __linktext='Created' sort='createdDate' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket' state=2}
            {/if}
            {if $state == 3}
                {sortlink __linktext='Created' sort='createdDate' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket' state=3}
            {/if}
            {if $state == 1}
                {sortlink __linktext='Created' sort='createdDate' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket'}
            {/if}        
        {/if}   
        </th>
        <th id="htitle" scope="col" align="left" valign="middle">
        {if $state}
            {if $state == 2}
            	{sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket' state=2}
            {/if}
            {if $state == 3}
            	{sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket' state=3}
            {/if}
            {if $state == 1}
            	{sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket'}
            {/if}
        {/if}
        </th>
        <th id="hstate" scope="col" align="left" valign="middle">
           {* {sortlink __linktext='State' sort='state' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket'} *}{gt text='Open'}
        </th>
        <th id="hintactions" scope="col" align="left" valign="middle" class="z-wrap z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='ticket' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        {if $kind eq 0 && $state ne 0}
        <td headers="hstate" align="center" valign="middle">
            {$ticket.id|muticketGetTicketState:$ticket.id}
        </td>
        {/if}   
        <td headers="hupdated" align="left" valign="middle">
            {$ticket.createdDate|dateformat:datetimelong}
        </td>    
        <td headers="htitle" align="left" valign="middle">
            {$ticket.title|notifyfilters:'muticket.filterhook.tickets'}
        </td>
        <td headers="hstate" align="left" valign="middle">
            {$ticket.state|yesno:true}
        </td>
        <td headers="hintactions" align="left" valign="middle" style="white-space: nowrap">
            <a href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$ticket.id}" title="{$ticket.title|replace:"\"":""}">
                {icon type='display' size='extrasmall' __alt='Details'}
            </a>
   {* {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_EDIT'}
            <a href="{modurl modname='MUTicket' type='user' func='edit' ot='ticket' id=$ticket.id}" title="{gt text='Edit'}">
                {icon type='edit' size='extrasmall' __alt='Edit'}
            </a>
            <a href="{modurl modname='MUTicket' type='user' func='edit' ot='ticket' astemplate=$ticket.id}" title="{gt text='Reuse for new item'}">
                {icon type='saveas' size='extrasmall' __alt='Reuse'}
            </a>
    {/checkpermissionblock}
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_DELETE'}
            <a href="{modurl modname='MUTicket' type='user' func='delete' ot='ticket' id=$ticket.id}" title="{gt text='Delete'}">
                {icon type='delete' size='extrasmall' __alt='Delete'}
            </a>
    {/checkpermissionblock} *}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-datatableempty">
          <td align="left" valign="top" colspan="9">
            {gt text='No tickets found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}

    {notifydisplayhooks eventname='muticket.ui_hooks.tickets.display_view' urlobject=$currentUrlObject assign='hooks'}
    {foreach key='hookname' item='hook' from=$hooks}
        {$hook}
    {/foreach}
</div>
</div>
{include file='user/footer.tpl'}

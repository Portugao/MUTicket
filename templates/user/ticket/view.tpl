{* purpose of this template: tickets view view in user area *}
<div class="muticket-ticket muticket-view">
{include file='user/header.tpl'}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='jquery-ui'}
{pageaddvar name='stylesheet' value='modules/MUTicket/style/jquery-ui-1.8.21.custom.css'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}
{if $supporteractive eq 0}
<div id="ticket_user_nosupport">
{gt text='Sorry. At the moment our support is not available!'}
</div>
{/if}
{if $ticketstate eq 2 || $ticketstate eq 3}
{if $ticketstate eq 2}
{gt text='Open Tickets Overview' assign='templateTitle'}
{/if}
{if $ticketstate eq 3}
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

{include file='user/ticket/view_quickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

<table class="z-datatable ticket_user_table">
    <colgroup>
        <col id="ccreated" />
        {if $kind eq 0 && $ticketstate ne 0}
        <col id="cstate" />
        {/if}
        <col id="ctitle" />
        {if $kind eq 0 && $ticketstate ne 0}
        <col id="cowner" />
        {/if}
        <col id="copen" />
        {if $kind eq 0 && $ticketstate ne 0}
        <col id="clabel" />
        {/if}
        {if $kind eq 0 && $ticketstate ne 0}
        <col id="cduedate" />
        {/if}
        <col id="cintactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hcreated" scope="col" align="left" valign="middle">
        {if $ticketstate}
            {if $ticketstate == 2}
                {sortlink __linktext='Created' sort='createdDate' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket' state=2}
            {/if}
            {if $ticketstate == 3}
                {sortlink __linktext='Created' sort='createdDate' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket' state=3}
            {/if}
            {if $ticketstate == 1}
                {sortlink __linktext='Created' sort='createdDate' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket'}
            {/if}        
        {/if}   
        </th>
        {if $kind eq 0 && $ticketstate ne 0}
        <th id="hstate" scope="col" align="center" valign="middle">
        {gt text='State'}
        </th>
        {/if}
        <th id="htitle" scope="col" align="left" valign="middle">
        {if $ticketstate}
            {if $ticketstate == 2}
            	{sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket' state=2}
            {/if}
            {if $ticketstate == 3}
            	{sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket' state=3}
            {/if}
            {if $ticketstate == 1}
            	{sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket'}
            {/if}
        {/if}
        </th>
        {if $kind eq 0 && $ticketstate ne 0}
        <th id="howner" scope="col" align="center" valign="middle">
        {gt text='Owner'}
        </th>
        {/if}
        <th id="hopen" scope="col" align="left" valign="middle">
           {* {sortlink __linktext='State' sort='state' currentsort=$sort sortdir=$sdir modname='MUTicket' type='user' func='view' ot='ticket'} *}{gt text='Open'}
        </th>
        {if $kind eq 0 && $ticketstate ne 0}
        <th id="hlabel" scope="col" align="center" valign="middle">
        {gt text='Label'}
        </th>
        {/if}
        {if $kind eq 0 && $ticketstate ne 0}
        <th id="hduedate" scope="col" align="center" valign="middle">
        {gt text='Due date'}
        </th>
        {/if}
        <th id="hintactions" scope="col" align="left" valign="middle" class="z-wrap z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='ticket' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="hupdated" align="left" valign="middle">
            {$ticket.createdDate|dateformat:datebrief}
        </td> 
        {if $kind eq 0 && $ticketstate ne 0}
        <td headers="hstate" align="center" valign="middle">
            <div class="muticket_ticket_state_change">
            <span class="muticket_ticket_edit_button">&nbsp;</span>
            <div class="muticket_currentstate">
            {if $ticket.currentState ne 0}
            {$ticket.currentState|muticketGetCurrentStateDatas}
            {else}
            {gt text='Not set'}
            {/if}
            </div>
            
        <div class="muticket_currentstate_form">
        <form action="{modurl modname='MUTicket' type='ajax' func='changeCurrentState' ticket=$ticket.id}" method="post">
            <select id="currentState" name="currentState">
            <option>{gt text='Set current state'}</option>
            {muticketSelectorCurrentState assign='States'}
            {foreach item='State' from=$States}
            <option value={$State.value}>{$State.text}</option>
            {/foreach}
            </select><br />
            <label for='statemessage'>{gt text='Send message?'}</label>
            <input type='checkbox' name='statemessage' value='1' /><br />
            <input type='hidden' name='actualsupporter' value={$data.id} />
            <input type='submit' value='Submit' />
        </form>   
        </div> 
        </div>        
        </td>
        {/if}      
        <td headers="htitle" align="left" valign="middle">
            {$ticket.title|notifyfilters:'muticket.filterhook.tickets'}
        </td>
        {if $kind eq 0 && $ticketstate ne 0}
        <td headers="howner" align="center" valign="middle">
        <div class="muticket_ticket_owner_change">
        <span class="muticket_ticket_edit_button">&nbsp;</span>
        <div class="muticket_owner">
        {if $ticket.owner gt 1}
            {$ticket.owner|profilelinkbyuid}
        {else}{gt text='Not set'}{/if}
        </div>
        <div class="muticket_owner_form">
        <form action="{modurl modname='MUTicket' type='ajax' func='changeSupporter' ticket=$ticket.id}" method="post">
            <select id="supporter" name="supporter">
            <option>{gt text='Set owner'}</option>
            {muticketSelectorSupporter assign='options'}
            {foreach item='data' from=$options}
            <option value={$data.text}>{$data.text}</option>
            {/foreach}
            </select><br />
            <label for='ownermessage'>{gt text='Send message?'}</label>
            <input type='checkbox' name='ownermessage' value='1' /><br />
            <input type='hidden' name='actualsupporter' value={$data.text} />
            <input type='submit' value='Submit' />
        </form>
        </div>
        </div>
        </td>
        {/if}  
        <td headers="hopen" align="left" valign="middle">
            {$ticket.state|yesno:true}
        </td>
        {if $kind eq 0 && $ticketstate ne 0}
        <td headers="hlabel" align="center" valign="middle">
            {if isset($ticket.labelticket) && count($ticket.labelticket > 0)}
            {include file='user/label/include_displayItemListManyIcons.tpl' items=$ticket.labelticket}
            {else}
            {gt text='Not set'}
            {/if}
            <a href="index.php?module=muticket&type=admin&func=edit&ot=ticket&id={$ticket.id}&kind=label&theme=printer" class="muticket_ticket_label_change"><img style="width: 10px; height: 10px;" src="images/icons/extrasmall/xedit.png" /></a>
        </td>
        {/if}
        {if $kind eq 0 && $ticketstate ne 0}
        <td headers="hduedate" align="center" valign="middle">
        <div class="muticket_ticket_date_change">
        <span class="muticket_ticket_edit_button">&nbsp;</span>
        <div class="muticket_duedate">
        {if $ticket.dueText ne ''}
        {$ticket.dueText}
        {else}
        {if $ticket.dueDate > $ticket.createdDate}
        {$ticket.dueDate|dateformat:datebrief}
        {else}
        {gt text='Not set'}
        {/if}
        {/if}
        </div>
        <div class="muticket_duedate_form">
        <form action="{modurl modname='MUTicket' type='ajax' func='changeSupporter' ticket=$ticket.id}" method="post">
            <input id='duedate' type='text' value={$ticket.dueDate|dateformat:datebrief}></input><img id="dueDate_img" class="clickable" alt="Datum auswählen" style="vertical-align: middle" src="http://zik135.webdesign-in-bremen.com/javascript/jscalendar/img.gif"><br />
            <input id='duetext' type='text' value={$ticket.dueText}></input><br />
            <input type='submit' value='Submit' />
        </form>        
        </td>
        <script type="text/javascript">
        /* <![CDATA[ */
        Calendar.setup(
        {
        inputField : "dueDate",
        ifFormat : "%Y-%m-%d %H:%M",
        showsTime : true,
        timeFormat : "24",
        singleClick : false,
        button : "dueDate_img",
        firstDay: 1
        }
        );
        /* ]]> */
        </script>
        {/if}    
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

 <script type="text/javascript" charset="utf-8">
        /* <![CDATA[ */
        var MU = jQuery.noConflict();

        MU(document).ready(function() {
        var statedialog = MU("<div title='Change state' id='statedialog'></div>");
        var labeldialog = MU("<div title='Change labels' id='labeldialog'></div>");

            MU(statedialog).dialog({
                show: "slow",
                autoOpen: false,
                width: 400,
                height: 400,
                modal: true,
                buttons: {
                    "{{gt text='Cancel'}}": function() {
                        MU(this).dialog("close");
                    }           	    
                }
            });
            
            MU(labeldialog).dialog({
                show: "slow",
                autoOpen: false,
                width: 400,
                height: 400,
                modal: true,
                buttons: {
                    "{{gt text='Cancel'}}": function() {
                        MU(this).dialog("close");
                    }           	    
                }
            });
            
            MU('.muticket_ticket_label_change').click(function(e){
                e.preventDefault();
              /*  MU(this).append("<img id='muticket_state_ajax' src='/images/ajax/icon_animated_busy.gif' />"); */
                var url = MU(this).attr('href');
                MU.get(url, function(ergebnis) {             
                    if (ergebnis) { 
                        MU(labeldialog).dialog('open');
                        MU(labeldialog).html(ergebnis);
                   }               
                });               
            }); 
            
            MU(".muticket_ticket_edit_button").toggle(
            function () {
            var user = MU(this).next();
            var form = user.next();
                form.show();
            },
            function () {
                MU(this).next().next().hide();             
             }             
             );
         
        });

        
        function tooltip(obj) {
            if (!obj.length) return;
            MU("body").append('<div id="tooltip" />');
            var tooltip = MU("#tooltip");
            var title;
            obj.hover(      
                function() {
                    title = MU(this).attr("title") ? MU(this).attr("title") : "{{gt text='No decription'}}";
                    MU(this).attr("title", "");
                    tooltip.html(title);
                    tooltip.stop(true,true)
                    .delay(50)
                    .fadeIn("fast")
                    .dequeue();
                }, 
                function() {
                   MU(this).attr("title", title);
                   tooltip.stop(true,true).fadeOut("fast");
                }).mousemove(function(e) {
                     tooltip.animate({
                     top:e.pageY + 10,
                     left:e.pageX + 10
                     },30);
                   });    
        }

        MU(document).ready(function() {
             tooltip(MU(".tooltip"));       
        });

        /* ]]> */
        </script>
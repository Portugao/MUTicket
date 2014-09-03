{* purpose of this template: rated tickets view of a supporter in admin area *}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='jquery-ui'}
<div class="muticket-ticket muticket-view">
{include file='admin/header.tpl'}
{gt text='Rated Tickets and statistics of the supporter:' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='view' size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
    <h2>{$supporter}</h2>
</div>

   {* {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='MUTicket' type='admin' func='view' ot='ticket'}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='MUTicket' type='admin' func='view' ot='ticket' all=1}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
    {/if} *}
    
<div id="ticket_table"><p id="rated_tickets" style="cursor: pointer;">{gt text='Show rated answers of this supporter!'}</p>
<div style="display: none; height: 200px; overflow: auto;"><table class="z-datatable">
    <colgroup>
        <col id="crating" />
       {* <col id="ctitle" /> *}
        <col id="ctext" />
        <col id="cdate" />
       {* <col id="cstate" />
        <col id="crated" />
        <col id="cparent" /> *}
        <col id="citemactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hrating" scope="col" class="z-left">
            {* {sortlink __linktext='Rating' sort='title' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'} *}
            {gt text='Rating'}
        </th>
       {* <th id="htitle" scope="col" class="z-left">
            {sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th> *}
        <th id="htext" scope="col" class="z-left">
           {* {sortlink __linktext='Text' sort='text' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'} *}
           {gt text='Text'}
        </th>
        <th id="hcreateddate" scope="col" class="z-left">
           {* {sortlink __linktext='Text' sort='text' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'} *}
           {gt text='Created Date'}
        </th>
      {*  <th id="hstate" scope="col" class="z-center">
            {sortlink __linktext='State' sort='state' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hrated" scope="col" class="z-center">
            {sortlink __linktext='Rated' sort='rated' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hparent" scope="col" class="z-left">
            {sortlink __linktext='Parent' sort='parent' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th> *}
        <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='ticket' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="hrating" class="z-left">
            {include file='admin/rating/include_displayItemListMany.tpl' items=$ticket.rating}
        </td>
       {* <td headers="htitle" class="z-left">
            {$ticket.title|notifyfilters:'muticket.filterhook.tickets'}
        </td> *}
        <td headers="htext" class="z-left">
            {$ticket.text}
        </td>
        <td headers="hcreated" class="z-left">
            {$ticket.createdDate|dateformat:datetimelong}
        </td>
      {*  <td headers="hstate" class="z-center">
            {$ticket.state|yesno:true}
        </td>
        <td headers="hrated" class="z-center">
            {$ticket.rated|yesno:true}
        </td>
        <td headers="hparent" class="z-left">
            {if isset($ticket.Parent) && $ticket.Parent ne null}
                <a href="{modurl modname='MUTicket' type='admin' func='display' ot='ticket' id=$ticket.Parent.id}">
                    {$ticket.Parent.title|default:""}
                </a>
                <a id="ticketItem{$ticket.id}_rel_{$ticket.Parent.id}Display" href="{modurl modname='MUTicket' type='admin' func='display' ot='ticket' id=$ticket.Parent.id theme='Printer'}" title="{gt text='Open quick view window'}" style="display: none">
                    {icon type='view' size='extrasmall' __alt='Quick view'}
                </a>
                <script type="text/javascript" charset="utf-8">
                /* <![CDATA[ */
                    document.observe('dom:loaded', function() {
                        muticketInitInlineWindow($('ticketItem{{$ticket.id}}_rel_{{$ticket.Parent.id}}Display'), '{{$ticket.Parent.title|replace:"'":""}}');
                    });
                /* ]]> */
                </script>
            {else}
                {gt text='Not set.'}
            {/if}
        </td> *}
        <td headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($ticket._actions) gt 0}
            {strip}
                {foreach item='option' from=$ticket._actions}
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
          <td class="z-left" colspan="9">
            {gt text='No tickets found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>
</div>
</div>

<div id="statistic_tickets">
<div id="statistic_tickets_left">
<div id="ticketsnumber"><h3>{gt text='Number of answers'}</h3>
{gt text='The supporter created'} <span>{$counttickets}</span> {gt text='answer for customers' plural='answers for customers' tag1=$counttickets count=$counttickets}</div>

<div id="ticketsrated"><h3>{gt text='Number of rated answers'}</h3>
{gt text='Customers rated'} <span>{$objectcount}</span> {gt text='answer of this supporter' plural ='answers of this supporter' tag1=$objectcount count=$objectcount}<br />
{gt text='That are '} <span>{$percent}{gt text='%'}</span> {gt text='of the total answers'}</div>
</div>
<div id="statistic_tickets_right">
<div id="ticketpercent"><h3>{gt text='Average of the customer rating for this supporter'}</h3>
{gt text='Customers have given'} <span>{$total}</span> {gt text='point by rating the answers of this supporter' plural='points by rating the answers of this supporter' tag1=$total count=$total}<br />
{gt text='That is an average of '} <span>{$average}</span> {gt text='point for the rating of customers' plural='points for the rating of customers' tag1=$average count=$average}</div></div> 
</div>

        <script type="text/javascript" charset="utf-8">
        /* <![CDATA[ */
        var MU = jQuery.noConflict();
        MU(document).ready(function() {
        	MU("#rated_tickets").click( function(e) {
            e.preventDefault();
            MU(this).css({"color":"red"});
            var tickets = MU(this).next();
            tickets.slideToggle(1500, 'easeInCirc');
            status++; 
        });   
        });
        /* ]]> */
        </script>  

   {* {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
    {/if} *}
</div>
{include file='admin/footer.tpl'}


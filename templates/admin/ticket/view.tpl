{* purpose of this template: tickets view view in admin area *}
<div class="muticket-ticket muticket-view">
{include file='admin/header.tpl'}
{gt text='Ticket list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='view' size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>

    {assign var='all' value=0}
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
    {/if}

<form class="z-form" id="tickets_view" action="{modurl modname='MUTicket' type='admin' func='handleselectedentries'}" method="post">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="ot" value="ticket" />

<table class="z-datatable">
    <colgroup>
        <col id="cselect" />
        <col id="ccreatedDate" />
        <col id="ctitle" />
        <col id="ctext" />
        <col id="cstate" />
       {* <col id="crated" /> *}
       {* <col id="cparent" /> *}
        <col id="citemactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hselect" scope="col" align="center" valign="middle">
            <input type="checkbox" id="toggle_tickets" />
        </th>
        <th id="hcreatedDate" scope="col" class="z-left">
            {sortlink __linktext='Created Date' sort='createdDate' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="htitle" scope="col" class="z-left">
            {sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="htext" scope="col" class="z-left">
            {sortlink __linktext='Text' sort='text' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hstate" scope="col" class="z-center">
            {gt text='Open'}
        </th>
       {* <th id="hrated" scope="col" class="z-center">
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
        <td headers="hselect" align="center" valign="top">
           <input type="checkbox" name="items[]" value="{$ticket.id}" class="ticket_checkbox" />
        </td>
        <td headers="htitle" class="z-left">
            {$ticket.createdDate|dateformat:datetimelong}
        </td>
        <td headers="htitle" class="z-left">
            {$ticket.title|notifyfilters:'muticket.filterhook.tickets'}
        </td>
        <td headers="htext" class="z-left">
            {$ticket.text|truncate:200}
        </td>
        <td headers="hstate" class="z-center">
            {$ticket.state|yesno:true}
        </td>
       {*  <td headers="hrated" class="z-center">
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
                <td id="itemactions{$ticket.id}" headers="hitemactions" class="z-right z-nowrap z-w02">
                    {if count($ticket._actions) gt 0}
                        {foreach item='option' from=$ticket._actions}
                            <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                        {/foreach}
                        {icon id="itemactions`$ticket.id`trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                        <script type="text/javascript">
                        /* <![CDATA[ */
                            document.observe('dom:loaded', function() {
                                muticketInitItemActions('ticket', 'view', 'itemactions{{$ticket.id}}');
                            });
                        /* ]]> */
                        </script>
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

    {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
    {/if}
            <fieldset>
            <label for="muticket_action">{gt text='With selected tickets'}</label>
            <select id="muticket_action" name="action">
                <option value="">{gt text='Choose action'}</option>
                <option value="delete" title="{gt text='Delete content permanently.'}">{gt text='Delete'}</option>
            </select>
            <input type="submit" value="{gt text='Submit'}" />
        </fieldset>
    </div>
</form>
</div>
{include file='admin/footer.tpl'}

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
    {{* init the "toggle all" functionality *}}
    if ($('toggle_tickets') != undefined) {
        $('toggle_tickets').observe('click', function (e) {
            Zikula.toggleInput('tickets_view');
            e.stop()
        });
    }
    });
/* ]]> */
</script>

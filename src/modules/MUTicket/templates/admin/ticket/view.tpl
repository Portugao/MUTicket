{* purpose of this template: tickets view view in admin area *}
<div class="muticket-ticket muticket-view">
{include file='admin/header.tpl'}
{gt text='Ticket list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='view' size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>


    {checkpermissionblock component='MUTicket::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create ticket' assign='createTitle'}
        <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='ticket'}" title="{$createTitle}" class="z-icon-es-add">
            {$createTitle}
        </a>
    {/checkpermissionblock}


<table class="z-datatable">
    <colgroup>
        <col id="ctitle" />
        <col id="ctext" />
        <col id="cparent_id" />
        <col id="cimages" />
        <col id="cfiles" />
        <col id="cstate" />
        <col id="ct_rating" />
        <col id="crated" />
        <col id="cparent" />
        <col id="cintactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="htitle" scope="col" align="left" valign="middle">
            {sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="htext" scope="col" align="left" valign="middle">
            {sortlink __linktext='Text' sort='text' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hparent_id" scope="col" align="right" valign="middle">
            {sortlink __linktext='Parent_id' sort='parent_id' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="himages" scope="col" align="left" valign="middle">
            {sortlink __linktext='Images' sort='images' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hfiles" scope="col" align="left" valign="middle">
            {sortlink __linktext='Files' sort='files' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hstate" scope="col" align="left" valign="middle">
            {sortlink __linktext='State' sort='state' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="ht_rating" scope="col" align="right" valign="middle">
            {sortlink __linktext='T_rating' sort='t_rating' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hrated" scope="col" align="right" valign="middle">
            {sortlink __linktext='Rated' sort='rated' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hparent" scope="col" align="left" valign="middle">
            {sortlink __linktext='Parent' sort='parent' currentsort=$sort sortdir=$sdir modname='MUTicket' type='admin' func='view' ot='ticket'}
        </th>
        <th id="hintactions" scope="col" align="left" valign="middle" class="z-wrap z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='ticket' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="htitle" align="left" valign="top">
            {$ticket.title|notifyfilters:'muticket.filterhook.tickets'}
        </td>
        <td headers="htext" align="left" valign="top">
            {$ticket.text}
        </td>
        <td headers="hparent_id" align="right" valign="top">
            {$ticket.parent_id}
        </td>
        <td headers="himages" align="left" valign="top">
            {if $ticket.images ne ''}
              <a href="{$ticket.imagesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
              {if $ticket.imagesMeta.isImage}
                  <img src="{$ticket.images|muticketImageThumb:$ticket.imagesFullPath:80:50}" width="80" height="50" alt="{$ticket.title|replace:"\"":""}" />
              {else}
                  {gt text='Download'} ({$ticket.imagesMeta.size|muticketGetFileSize:$ticket.imagesFullPath:false:false})
              {/if}
              </a>
            {else}&nbsp;{/if}

        </td>
        <td headers="hfiles" align="left" valign="top">
            {if $ticket.files ne ''}
              <a href="{$ticket.filesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.filesMeta.isImage} rel="imageviewer[ticket]"{/if}>
              {if $ticket.filesMeta.isImage}
                  <img src="{$ticket.files|muticketImageThumb:$ticket.filesFullPath:80:50}" width="80" height="50" alt="{$ticket.title|replace:"\"":""}" />
              {else}
                  {gt text='Download'} ({$ticket.filesMeta.size|muticketGetFileSize:$ticket.filesFullPath:false:false})
              {/if}
              </a>
            {else}&nbsp;{/if}

        </td>
        <td headers="hstate" align="left" valign="top">
            {$ticket.state}
        </td>
        <td headers="ht_rating" align="right" valign="top">
            {$ticket.t_rating}
        </td>
        <td headers="hrated" align="right" valign="top">
            {$ticket.rated}
        </td>
        <td headers="hparent" align="left" valign="top">
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
        </td>
        <td headers="hintactions" align="left" valign="top" style="white-space: nowrap">
            <a href="{modurl modname='MUTicket' type='admin' func='display' ot='ticket' id=$ticket.id}" title="{$ticket.title|replace:"\"":""}">
                {icon type='display' size='extrasmall' __alt='Details'}
            </a>
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_EDIT'}
            <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='ticket' id=$ticket.id}" title="{gt text='Edit'}">
                {icon type='edit' size='extrasmall' __alt='Edit'}
            </a>
            <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='ticket' astemplate=$ticket.id}" title="{gt text='Reuse for new item'}">
                {icon type='saveas' size='extrasmall' __alt='Reuse'}
            </a>
    {/checkpermissionblock}
    {checkpermissionblock component='MUTicket::' instance='.*' level='ACCESS_DELETE'}
            <a href="{modurl modname='MUTicket' type='admin' func='delete' ot='ticket' id=$ticket.id}" title="{gt text='Delete'}">
                {icon type='delete' size='extrasmall' __alt='Delete'}
            </a>
    {/checkpermissionblock}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-admintableempty">
          <td align="left" valign="top" colspan="9">
            {gt text='No tickets found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
</div>
{include file='admin/footer.tpl'}

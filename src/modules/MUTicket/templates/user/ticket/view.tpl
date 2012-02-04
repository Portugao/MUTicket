{* purpose of this template: tickets view view in user area *}
{include file='user/header.tpl'}
<div class="muticket-ticket muticket-view">
{gt text='Ticket list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>


    {checkpermissionblock component='MUTicket::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create ticket' assign='createTitle'}
        <a href="{modurl modname='MUTicket' type='user' func='edit' ot='ticket'}" title="{$createTitle}" class="z-icon-es-add">
            {$createTitle}
        </a>
    {/checkpermissionblock}

    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='MUTicket' type='user' func='view' ot='ticket'}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='MUTicket' type='user' func='view' ot='ticket' all=1}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
    {/if}

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
        <col id="citemactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="htitle" scope="col" class="z-left">
            {sortlink __linktext='Title' sort='title' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="htext" scope="col" class="z-left">
            {sortlink __linktext='Text' sort='text' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="hparent_id" scope="col" class="z-right">
            {sortlink __linktext='Parent_id' sort='parent_id' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="himages" scope="col" class="z-left">
            {sortlink __linktext='Images' sort='images' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="hfiles" scope="col" class="z-left">
            {sortlink __linktext='Files' sort='files' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="hstate" scope="col" class="z-center">
            {sortlink __linktext='State' sort='state' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="ht_rating" scope="col" class="z-center">
            {sortlink __linktext='T_rating' sort='t_rating' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="hrated" scope="col" class="z-center">
            {sortlink __linktext='Rated' sort='rated' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="hparent" scope="col" class="z-left">
            {sortlink __linktext='Parent' sort='parent' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='ticket'}
        </th>
        <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='ticket' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="htitle" class="z-left">
            {$ticket.title|notifyfilters:'muticket.filterhook.tickets'}
        </td>
        <td headers="htext" class="z-left">
            {$ticket.text}
        </td>
        <td headers="hparent_id" class="z-right">
            {$ticket.parent_id}
        </td>
        <td headers="himages" class="z-left">
            {if $ticket.images ne ''}
              <a href="{$ticket.imagesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
              {if $ticket.imagesMeta.isImage}
                  <img src="{$ticket.images|muticketImageThumb:$ticket.imagesFullPath:32:20}" width="32" height="20" alt="{$ticket.title|replace:"\"":""}" />
              {else}
                  {gt text='Download'} ({$ticket.imagesMeta.size|muticketGetFileSize:$ticket.imagesFullPath:false:false})
              {/if}
              </a>
            {else}&nbsp;{/if}

        </td>
        <td headers="hfiles" class="z-left">
            {if $ticket.files ne ''}
              <a href="{$ticket.filesFullPathURL}" title="{$ticket.title|replace:"\"":""}"{if $ticket.filesMeta.isImage} rel="imageviewer[ticket]"{/if}>
              {if $ticket.filesMeta.isImage}
                  <img src="{$ticket.files|muticketImageThumb:$ticket.filesFullPath:32:20}" width="32" height="20" alt="{$ticket.title|replace:"\"":""}" />
              {else}
                  {gt text='Download'} ({$ticket.filesMeta.size|muticketGetFileSize:$ticket.filesFullPath:false:false})
              {/if}
              </a>
            {else}&nbsp;{/if}

        </td>
        <td headers="hstate" class="z-center">
            {$ticket.state|yesno:true}
        </td>
        <td headers="ht_rating" class="z-center">
            {$ticket.t_rating|yesno:true}
        </td>
        <td headers="hrated" class="z-center">
            {$ticket.rated|yesno:true}
        </td>
        <td headers="hparent" class="z-left">
            {if isset($ticket.Parent) && $ticket.Parent ne null}
                <a href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$ticket.Parent.id}">
                    {$ticket.Parent.title|default:""}
                </a>
                <a id="ticketItem{$ticket.id}_rel_{$ticket.Parent.id}Display" href="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$ticket.Parent.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" style="display: none">
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
        <td headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($ticket._actions) gt 0}
                {foreach item='option' from=$ticket._actions}
                    <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                {/foreach}
            {/if}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-datatableempty">
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

    {notifydisplayhooks eventname='muticket.ui_hooks.tickets.display_view' urlobject=$currentUrlObject assign='hooks'}
    {foreach key='hookname' item='hook' from=$hooks}
        {$hook}
    {/foreach}
</div>
</div>
{include file='user/footer.tpl'}


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

    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='MUTicket' type='user' func='view' ot='rating'}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='MUTicket' type='user' func='view' ot='rating' all=1}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
    {/if}

<table class="z-datatable">
    <colgroup>
        <col id="cratingvalue" />
        <col id="citemactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hratingvalue" scope="col" class="z-right">
            {sortlink __linktext='Ratingvalue' sort='ratingvalue' currentsort=$sort sortdir=$sdir all=$all modname='MUTicket' type='user' func='view' ot='rating'}
        </th>
        <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='rating' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="hratingvalue" class="z-right">
            {$rating.ratingvalue|notifyfilters:'muticket.filterhook.ratings'}
        </td>
        <td headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($rating._actions) gt 0}
            {strip}
                {foreach item='option' from=$rating._actions}
                    <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>
                        {icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}
                    </a>
                {/foreach}
            {/strip}
            {/if}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-datatableempty">
          <td class="z-left" colspan="2">
            {gt text='No ratings found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
    {/if}

    {notifydisplayhooks eventname='muticket.ui_hooks.ratings.display_view' urlobject=$currentUrlObject assign='hooks'}
    {foreach key='hookname' item='hook' from=$hooks}
        {$hook}
    {/foreach}
</div>
</div>
{include file='user/footer.tpl'}


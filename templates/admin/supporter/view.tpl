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
<form class="z-form" id="supporters_view" action="{modurl modname='MUTicket' type='admin' func='handleselectedentries'}" method="post">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="ot" value="supporter" />

<table class="z-datatable">
    <colgroup>
        <col id="cselect" />
        <col id="cusername" />
        <col id="csupportcats" />
        <col id="cstate" />
        <col id="cintactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hselect" scope="col" align="center" valign="middle">
            <input type="checkbox" id="toggle_supporters" />
        </th>
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
        <td headers="hselect" align="center" valign="top">
           <input type="checkbox" name="items[]" value="{$supporter.id}" class="supporter_checkbox" />
        </td>
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
        <td id="itemactions{$supporter.id}" headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($supporter._actions) gt 0}
                {foreach item='option' from=$supporter._actions}
                    <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                {/foreach}
                {icon id="itemactions`$supporter.id`trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                <script type="text/javascript">
                /* <![CDATA[ */
                    document.observe('dom:loaded', function() {
                        muticketInitItemActions('supporter', 'view', 'itemactions{{$supporter.id}}');
                    });
                /* ]]> */
                </script>
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
        {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
        {/if}
            <fieldset>
            <label for="muticket_action">{gt text='With selected supporters'}</label>
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
    if ($('toggle_supporters') != undefined) {
        $('toggle_supporters').observe('click', function (e) {
            Zikula.toggleInput('supporters_view');
            e.stop()
        });
    }
    });
/* ]]> */
</script>

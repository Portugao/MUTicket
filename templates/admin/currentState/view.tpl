{* purpose of this template: current states view view in admin area *}
{include file='admin/header.tpl'}
<div class="muticket-currentstate muticket-view">
{gt text='Current state list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='view' size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>

{checkpermissionblock component='MUTicket:CurrentState:' instance='::' level='ACCESS_EDIT'}
    {gt text='Create current state' assign='createTitle'}
    <a href="{modurl modname='MUTicket' type='admin' func='edit' ot='currentState'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
{/checkpermissionblock}
{assign var='own' value=0}
{if isset($showOwnEntries) && $showOwnEntries eq 1}
    {assign var='own' value=1}
{/if}
{assign var='all' value=0}
{if isset($showAllEntries) && $showAllEntries eq 1}
    {gt text='Back to paginated view' assign='linkTitle'}
    <a href="{modurl modname='MUTicket' type='admin' func='view' ot='currentState'}" title="{$linkTitle}" class="z-icon-es-view">
        {$linkTitle}
    </a>
    {assign var='all' value=1}
{else}
    {gt text='Show all entries' assign='linkTitle'}
    <a href="{modurl modname='MUTicket' type='admin' func='view' ot='currentState' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
{/if}

{include file='admin/currentState/view_quickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

<form class="z-form" id="currentStates_view" action="{modurl modname='MUTicket' type='admin' func='handleselectedentries'}" method="post">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="ot" value="currentState" />
        <table class="z-datatable">
            <colgroup>
                <col id="cselect" />
                <col id="ctitle" />
                <col id="cdescription" />
                <col id="cuploadicon" />
                <col id="citemactions" />
            </colgroup>
            <thead>
            <tr>
                <th id="hselect" scope="col" align="center" valign="middle">
                    <input type="checkbox" id="toggle_currentStates" />
                </th>
                <th id="htitle" scope="col" class="z-left">
                    {sortlink __linktext='Title' currentsort=$sort modname='MUTicket' type='admin' func='view' ot='currentState' sort='title' sortdir=$sdir all=$all own=$own workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize}
                </th>
                <th id="hdescription" scope="col" class="z-left">
                    {sortlink __linktext='Description' currentsort=$sort modname='MUTicket' type='admin' func='view' ot='currentState' sort='description' sortdir=$sdir all=$all own=$own workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize}
                </th>
                <th id="huploadicon" scope="col" class="z-left">
                    {sortlink __linktext='Upload icon' currentsort=$sort modname='MUTicket' type='admin' func='view' ot='currentState' sort='uploadIcon' sortdir=$sdir all=$all own=$own workflowState=$workflowState searchterm=$searchterm pageSize=$pageSize}
                </th>
                <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
            </tr>
            </thead>
            <tbody>
        
        {foreach item='currentState' from=$items}
            <tr class="{cycle values='z-odd, z-even'}">
                <td headers="hselect" align="center" valign="top">
                    <input type="checkbox" name="items[]" value="{$currentState.id}" class="currentStates_checkbox" />
                </td>
                <td headers="htitle" class="z-left">
                    <a href="{modurl modname='MUTicket' type='admin' func='display' ot='currentState' id=$currentState.id}" title="{gt text='View detail page'}">{$currentState.title|notifyfilters:'muticket.filterhook.currentstates'}</a>
                </td>
                <td headers="hdescription" class="z-left">
                    {$currentState.description}
                </td>
                <td headers="huploadicon" class="z-left">
                    {if $currentState.uploadIcon ne ''}
                      <a href="{$currentState.uploadIconFullPathURL}" title="{$currentState.title|replace:"\"":""}"{if $currentState.uploadIconMeta.isImage} rel="imageviewer[currentstate]"{/if}>
                      {if $currentState.uploadIconMeta.isImage}
                          {thumb image=$currentState.uploadIconFullPath objectid="currentState-`$currentState.id`" preset=$currentStateThumbPresetUploadIcon tag=true img_alt=$currentState.title}
                      {else}
                          {gt text='Download'} ({$currentState.uploadIconMeta.size|muticketGetFileSize:$currentState.uploadIconFullPath:false:false})
                      {/if}
                      </a>
                    {else}&nbsp;{/if}
                </td>
                <td id="itemactions{$currentState.id}" headers="hitemactions" class="z-right z-nowrap z-w02">
                    {if count($currentState._actions) gt 0}
                        {foreach item='option' from=$currentState._actions}
                            <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                        {/foreach}
                        {icon id="itemactions`$currentState.id`trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                        <script type="text/javascript">
                        /* <![CDATA[ */
                            document.observe('dom:loaded', function() {
                                muticketInitItemActions('currentState', 'view', 'itemactions{{$currentState.id}}');
                            });
                        /* ]]> */
                        </script>
                    {/if}
                </td>
            </tr>
        {foreachelse}
            <tr class="z-admintableempty">
              <td class="z-left" colspan="5">
            {gt text='No current states found.'}
              </td>
            </tr>
        {/foreach}
        
            </tbody>
        </table>
        
        {if !isset($showAllEntries) || $showAllEntries ne 1}
            {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' modname='MUTicket' type='admin' func='view' ot='currentState'}
        {/if}
        <fieldset>
            <label for="muticket_action">{gt text='With selected current states'}</label>
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
    if ($('toggle_currentStates') != undefined) {
        $('toggle_currentStates').observe('click', function (e) {
            Zikula.toggleInput('currentStates_view');
            e.stop()
        });
    }
    });
/* ]]> */
</script>

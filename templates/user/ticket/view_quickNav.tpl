{* purpose of this template: tickets view filter form in user area *}
{checkpermissionblock component='MUTicket:Ticket:' instance='::' level='ACCESS_EDIT'}
{assign var='objectType' value='ticket'}
<form action="{$modvars.ZConfig.entrypoint|default:'index.php'}" method="get" id="muticketTicketQuickNavForm" class="muticketQuickNavForm">
    <fieldset>
        <h3>{gt text='Quick navigation'}</h3>
        <input type="hidden" name="module" value="{modgetinfo modname='MUTicket' info='url'}" />
        <input type="hidden" name="type" value="user" />
        <input type="hidden" name="func" value="view" />
        <input type="hidden" name="ot" value="ticket" />
        <input type="hidden" name="all" value="{$all|default:0}" />
        <input type="hidden" name="own" value="{$own|default:0}" />
        {gt text='All' assign='lblDefault'}
        {if !isset($categoryFilter) || $categoryFilter eq true}
            {modapifunc modname='MUTicket' type='category' func='getAllProperties' ot=$objectType assign='properties'}
            {if $properties ne null && is_array($properties)}
                {gt text='All' assign='lblDefault'}
                {nocache}
                {foreach key='propertyName' item='propertyId' from=$properties}
                    {modapifunc modname='MUTicket' type='category' func='hasMultipleSelection' ot=$objectType registry=$propertyName assign='hasMultiSelection'}
                    {gt text='Category' assign='categoryLabel'}
                    {assign var='categorySelectorId' value='catid'}
                    {assign var='categorySelectorName' value='catid'}
                    {assign var='categorySelectorSize' value='1'}
                    {if $hasMultiSelection eq true}
                        {gt text='Categories' assign='categoryLabel'}
                        {assign var='categorySelectorName' value='catids'}
                        {assign var='categorySelectorId' value='catids__'}
                        {assign var='categorySelectorSize' value='5'}
                    {/if}
                    <label for="{$categorySelectorId}{$propertyName}">{$categoryLabel}</label>
                    &nbsp;
                    {selector_category name="`$categorySelectorName``$propertyName`" field='id' selectedValue=$catIdList.$propertyName categoryRegistryModule='MUTicket' categoryRegistryTable=$objectType categoryRegistryProperty=$propertyName defaultText=$lblDefault editLink=false multipleSize=$categorySelectorSize}
                {/foreach}
                {/nocache}
            {/if}
        {/if}
        {if !isset($ticketFilter) || $ticketFilter eq true}
            <label for="parent">{gt text='Tickets'}</label>
            {modapifunc modname='MUTicket' type='selection' func='getEntities' ot='ticket' orderBy='tbl.title' slimMode=true assign='listEntries'}
            <select id="parent" name="parent">
                <option value="">{$lblDefault}</option>
            {foreach item='option' from=$listEntries}
                {assign var='entryId' value=$option.id}
                <option value="{$entryId}"{if $entryId eq $parent} selected="selected"{/if}>{$option.title}</option>
            {/foreach}
            </select>
        {/if}
        {if !isset($workflowStateFilter) || $workflowStateFilter eq true}
            <label for="workflowState">{gt text='Workflow state'}</label>
            <select id="workflowState" name="workflowState">
                <option value="">{$lblDefault}</option>
            {foreach item='option' from=$workflowStateItems}
            <option value="{$option.value}"{if $option.title ne ''} title="{$option.title|safetext}"{/if}{if $option.value eq $workflowState} selected="selected"{/if}>{$option.text|safetext}</option>
            {/foreach}
            </select>
        {/if}
        {if !isset($currentStateFilter) || $currentStateFilter eq true}
            <label for="currentState">{gt text='Current state'}</label>
            <select id="currentState" name="currentState">
                <option value="">{$lblDefault}</option>
            {foreach item='option' from=$currentStateItems}
            <option value="{$option.value}"{if $option.title ne ''} title="{$option.title|safetext}"{/if}{if $option.value eq $currentState} selected="selected"{/if}>{$option.text|safetext}</option>
            {/foreach}
            </select>
        {/if}
        {if !isset($ownerFilter) || $ownerFilter eq true}
            <label for="owner">{gt text='Owner'}</label>
            {selector_user name='owner' selectedValue=$owner defaultText=$lblDefault}
        {/if}
        {if !isset($searchFilter) || $searchFilter eq true}
            <label for="searchterm">{gt text='Search'}:</label>
            <input type="text" id="searchterm" name="searchterm" value="{$searchterm}" />
        {/if}
        {if !isset($sorting) || $sorting eq true}
            <label for="sortby">{gt text='Sort by'}</label>
            &nbsp;
            <select id="sortby" name="sort">
            <option value="id"{if $sort eq 'id'} selected="selected"{/if}>{gt text='Id'}</option>
            <option value="workflowState"{if $sort eq 'workflowState'} selected="selected"{/if}>{gt text='Workflow state'}</option>
            <option value="title"{if $sort eq 'title'} selected="selected"{/if}>{gt text='Title'}</option>
            <option value="text"{if $sort eq 'text'} selected="selected"{/if}>{gt text='Text'}</option>
            <option value="parent_id"{if $sort eq 'parent_id'} selected="selected"{/if}>{gt text='Parent_id'}</option>
            <option value="images"{if $sort eq 'images'} selected="selected"{/if}>{gt text='Images'}</option>
            <option value="files"{if $sort eq 'files'} selected="selected"{/if}>{gt text='Files'}</option>
            <option value="state"{if $sort eq 'state'} selected="selected"{/if}>{gt text='State'}</option>
            <option value="rated"{if $sort eq 'rated'} selected="selected"{/if}>{gt text='Rated'}</option>
            <option value="owner"{if $sort eq 'owner'} selected="selected"{/if}>{gt text='Owner'}</option>
            <option value="dueDate"{if $sort eq 'dueDate'} selected="selected"{/if}>{gt text='Due date'}</option>
            <option value="dueText"{if $sort eq 'dueText'} selected="selected"{/if}>{gt text='Due text'}</option>
            <option value="currentState"{if $sort eq 'currentState'} selected="selected"{/if}>{gt text='Current state'}</option>
            <option value="createdDate"{if $sort eq 'createdDate'} selected="selected"{/if}>{gt text='Creation date'}</option>
            <option value="createdUserId"{if $sort eq 'createdUserId'} selected="selected"{/if}>{gt text='Creator'}</option>
            <option value="updatedDate"{if $sort eq 'updatedDate'} selected="selected"{/if}>{gt text='Update date'}</option>
            </select>
            <select id="sortdir" name="sortdir">
                <option value="asc"{if $sdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
                <option value="desc"{if $sdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
            </select>
        {else}
            <input type="hidden" name="sort" value="{$sort}" />
            <input type="hidden" name="sdir" value="{if $sdir eq 'desc'}asc{else}desc{/if}" />
        {/if}
        {if !isset($pageSizeSelector) || $pageSizeSelector eq true}
            <label for="num">{gt text='Page size'}</label>
            &nbsp;
            <select id="num" name="num">
                <option value="5"{if $pageSize eq 5} selected="selected"{/if}>5</option>
                <option value="10"{if $pageSize eq 10} selected="selected"{/if}>10</option>
                <option value="15"{if $pageSize eq 15} selected="selected"{/if}>15</option>
                <option value="20"{if $pageSize eq 20} selected="selected"{/if}>20</option>
                <option value="30"{if $pageSize eq 30} selected="selected"{/if}>30</option>
                <option value="50"{if $pageSize eq 50} selected="selected"{/if}>50</option>
                <option value="100"{if $pageSize eq 100} selected="selected"{/if}>100</option>
            </select>
        {/if}
        {if !isset($stateFilter) || $stateFilter eq true}
            <label for="state">{gt text='State'}</label>
            <select id="state" name="state">
                <option value="">{$lblDefault}</option>
            {foreach item='option' from=$stateItems}
                <option value="{$option.value}"{if $option.value eq $state} selected="selected"{/if}>{$option.text|safetext}</option>
            {/foreach}
            </select>
        {/if}
        {if !isset($ratedFilter) || $ratedFilter eq true}
            <label for="rated">{gt text='Rated'}</label>
            <select id="rated" name="rated">
                <option value="">{$lblDefault}</option>
            {foreach item='option' from=$ratedItems}
                <option value="{$option.value}"{if $option.value eq $rated} selected="selected"{/if}>{$option.text|safetext}</option>
            {/foreach}
            </select>
        {/if}
        <input type="submit" name="updateview" id="quicknav_submit" value="{gt text='OK'}" />
    </fieldset>
</form>

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        muticketInitQuickNavigation('ticket', 'user');
        {{if isset($searchFilter) && $searchFilter eq false}}
            {{* we can hide the submit button if we have no quick search field *}}
            $('quicknav_submit').addClassName('z-hide');
        {{/if}}
    });
/* ]]> */
</script>
{/checkpermissionblock}

{* Purpose of this template: Display a popup selector for Forms and Content integration *}
{assign var='baseID' value='ticket'}
<div id="{$baseID}_preview" style="float: right; width: 300px; border: 1px dotted #a3a3a3; padding: .2em .5em; margin-right: 1em">
    <p><strong>{gt text='Ticket information'}</strong></p>
    {img id='ajax_indicator' modname='core' set='ajax' src='indicator_circle.gif' alt='' class='z-hide'}
    <div id="{$baseID}_previewcontainer">&nbsp;</div>
</div>
<br />
<br />
{assign var='leftSide' value=' style="float: left; width: 10em"'}
{assign var='rightSide' value=' style="float: left"'}
{assign var='break' value=' style="clear: left"'}

{if $properties ne null && is_array($properties)}
    {gt text='All' assign='lblDefault'}
    {nocache}
    {foreach key='propertyName' item='propertyId' from=$properties}
        <p>
            {modapifunc modname='MUTicket' type='category' func='hasMultipleSelection' ot='ticket' registry=$propertyName assign='hasMultiSelection'}
            {gt text='Category' assign='categoryLabel'}
            {assign var='categorySelectorId' value='catid'}
            {assign var='categorySelectorName' value='catid'}
            {assign var='categorySelectorSize' value='1'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' assign='categoryLabel'}
                {assign var='categorySelectorName' value='catids'}
                {assign var='categorySelectorId' value='catids__'}
                {assign var='categorySelectorSize' value='8'}
            {/if}
            <label for="{$baseID}_{$categorySelectorId}{$propertyName}"{$leftSide}>{$categoryLabel}:</label>
            &nbsp;
            {selector_category name="`$baseID`_`$categorySelectorName``$propertyName`" field='id' selectedValue=$catIds.$propertyName categoryRegistryModule='MUTicket' categoryRegistryTable=$objectType categoryRegistryProperty=$propertyName defaultText=$lblDefault editLink=false multipleSize=$categorySelectorSize}
            <br{$break} />
        </p>
    {/foreach}
    {/nocache}
{/if}
<p>
    <label for="{$baseID}_id"{$leftSide}>{gt text='Ticket'}:</label>
    <select id="{$baseID}_id" name="id"{$rightSide}>
        {foreach item='ticket' from=$items}{strip}
            <option value="{$ticket.id}"{if $selectedId eq $ticket.id} selected="selected"{/if}>
                {$ticket.title}
            </option>{/strip}
        {foreachelse}
            <option value="0">{gt text='No entries found.'}</option>
        {/foreach}
    </select>
    <br{$break} />
</p>
<p>
    <label for="{$baseID}_sort"{$leftSide}>{gt text='Sort by'}:</label>
    <select id="{$baseID}_sort" name="sort"{$rightSide}>
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
    <select id="{$baseID}_sortdir" name="sortdir">
        <option value="asc"{if $sortdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
        <option value="desc"{if $sortdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
    </select>
    <br{$break} />
</p>
<p>
    <label for="{$baseID}_searchterm"{$leftSide}>{gt text='Search for'}:</label>
    <input type="text" id="{$baseID}_searchterm" name="searchterm"{$rightSide} />
    <input type="button" id="MUTicket_gosearch" name="gosearch" value="{gt text='Filter'}" />
    <br{$break} />
</p>
<br />
<br />

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        muticket.itemSelector.onLoad('{{$baseID}}', {{$selectedId|default:0}});
    });
/* ]]> */
</script>

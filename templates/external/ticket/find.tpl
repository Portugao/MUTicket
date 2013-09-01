{* Purpose of this template: Display a popup selector of tickets for scribite integration *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}">
<head>
    <title>{gt text='Search and select ticket'}</title>
    <link type="text/css" rel="stylesheet" href="{$baseurl}style/core.css" />
    <link type="text/css" rel="stylesheet" href="{$baseurl}modules/MUTicket/style/style.css" />
    <link type="text/css" rel="stylesheet" href="{$baseurl}modules/MUTicket/style/finder.css" />
    {assign var='ourEntry' value=$modvars.ZConfig.entrypoint}
    <script type="text/javascript">/* <![CDATA[ */
        if (typeof(Zikula) == 'undefined') {var Zikula = {};}
        Zikula.Config = {'entrypoint': '{{$ourEntry|default:'index.php'}}', 'baseURL': '{{$baseurl}}'}; /* ]]> */</script>
        <script type="text/javascript" src="{$baseurl}javascript/ajax/proto_scriptaculous.combined.min.js"></script>
        <script type="text/javascript" src="{$baseurl}javascript/helpers/Zikula.js"></script>
        <script type="text/javascript" src="{$baseurl}javascript/livepipe/livepipe.combined.min.js"></script>
        <script type="text/javascript" src="{$baseurl}javascript/helpers/Zikula.UI.js"></script>
        <script type="text/javascript" src="{$baseurl}javascript/helpers/Zikula.ImageViewer.js"></script>
{*            <script type="text/javascript" src="{$baseurl}javascript/ajax/original_uncompressed/prototype.js"></script>
    <script type="text/javascript" src="{$baseurl}javascript/ajax/original_uncompressed/scriptaculous.js"></script>
    <script type="text/javascript" src="{$baseurl}javascript/ajax/original_uncompressed/dragdrop.js"></script>
    <script type="text/javascript" src="{$baseurl}javascript/ajax/original_uncompressed/effects.js"></script>*}
    <script type="text/javascript" src="{$baseurl}modules/MUTicket/javascript/MUTicket_finder.js"></script>
{if $editorName eq 'tinymce'}
    <script type="text/javascript" src="{$baseurl}modules/Scribite/includes/tinymce/tiny_mce_popup.js"></script>
{/if}
</head>
<body>
    <p>{gt text='Switch to'}:
    <a href="{modurl modname='MUTicket' type='external' func='finder' objectType='rating' editor=$editorName}" title="{gt text='Search and select rating'}">{gt text='Ratings'}</a> | 
    <a href="{modurl modname='MUTicket' type='external' func='finder' objectType='supporter' editor=$editorName}" title="{gt text='Search and select supporter'}">{gt text='Supporters'}</a> | 
    <a href="{modurl modname='MUTicket' type='external' func='finder' objectType='currentState' editor=$editorName}" title="{gt text='Search and select current state'}">{gt text='Current states'}</a> | 
    <a href="{modurl modname='MUTicket' type='external' func='finder' objectType='label' editor=$editorName}" title="{gt text='Search and select label'}">{gt text='Labels'}</a>
    </p>
    <form action="{$ourEntry|default:'index.php'}" id="selectorForm" method="get" class="z-form">
    <div>
        <input type="hidden" name="module" value="MUTicket" />
        <input type="hidden" name="type" value="external" />
        <input type="hidden" name="func" value="finder" />
        <input type="hidden" name="objectType" value="{$objectType}" />
        <input type="hidden" name="editor" id="editorName" value="{$editorName}" />

        <fieldset>
            <legend>{gt text='Search and select ticket'}</legend>

            {if $properties ne null && is_array($properties)}
                {gt text='All' assign='lblDefault'}
                {nocache}
                {foreach key='propertyName' item='propertyId' from=$properties}
                    <div class="z-formrow categoryselector">
                        {modapifunc modname='MUTicket' type='category' func='hasMultipleSelection' ot=$objectType registry=$propertyName assign='hasMultiSelection'}
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
                        <label for="{$categorySelectorId}{$propertyName}">{$categoryLabel}</label>
                        &nbsp;
                        {selector_category name="`$categorySelectorName``$propertyName`" field='id' selectedValue=$catIds.$propertyName categoryRegistryModule='MUTicket' categoryRegistryTable=$objectType categoryRegistryProperty=$propertyName defaultText=$lblDefault editLink=false multipleSize=$categorySelectorSize}
                        <span class="z-sub z-formnote">{gt text='This is an optional filter.'}</span>
                    </div>
                {/foreach}
                {/nocache}
            {/if}

            <div class="z-formrow">
                <label for="MUTicket_pasteas">{gt text='Paste as'}:</label>
                <select id="MUTicket_pasteas" name="pasteas">
                    <option value="1">{gt text='Link to the ticket'}</option>
                    <option value="2">{gt text='ID of ticket'}</option>
                </select>
            </div>
            <br />

            <div class="z-formrow">
                <label for="MUTicket_objectid">{gt text='Ticket'}:</label>
                <div id="muticketitemcontainer">
                    <ul>
                    {foreach item='ticket' from=$items}
                        <li>
                            <a href="#" onclick="muticket.finder.selectItem({$ticket.id})" onkeypress="muticket.finder.selectItem({$ticket.id})">
                                {$ticket.title}
                            </a>
                            <input type="hidden" id="url{$ticket.id}" value="{modurl modname='MUTicket' type='user' func='display' ot='ticket' id=$ticket.id fqurl=true}" />
                            <input type="hidden" id="title{$ticket.id}" value="{$ticket.title|replace:"\"":""}" />
                            <input type="hidden" id="desc{$ticket.id}" value="{capture assign='description'}{if $ticket.text ne ''}{$ticket.text}{/if}
                            {/capture}{$description|strip_tags|replace:"\"":""}" />
                        </li>
                    {foreachelse}
                        <li>{gt text='No entries found.'}</li>
                    {/foreach}
                    </ul>
                </div>
            </div>

            <div class="z-formrow">
                <label for="MUTicket_sort">{gt text='Sort by'}:</label>
                <select id="MUTicket_sort" name="sort" style="width: 150px" class="z-floatleft" style="margin-right: 10px">
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
                <select id="MUTicket_sortdir" name="sortdir" style="width: 100px">
                    <option value="asc"{if $sortdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
                    <option value="desc"{if $sortdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
                </select>
            </div>

            <div class="z-formrow">
                <label for="MUTicket_pagesize">{gt text='Page size'}:</label>
                <select id="MUTicket_pagesize" name="num" style="width: 50px; text-align: right">
                    <option value="5"{if $pager.itemsperpage eq 5} selected="selected"{/if}>5</option>
                    <option value="10"{if $pager.itemsperpage eq 10} selected="selected"{/if}>10</option>
                    <option value="15"{if $pager.itemsperpage eq 15} selected="selected"{/if}>15</option>
                    <option value="20"{if $pager.itemsperpage eq 20} selected="selected"{/if}>20</option>
                    <option value="30"{if $pager.itemsperpage eq 30} selected="selected"{/if}>30</option>
                    <option value="50"{if $pager.itemsperpage eq 50} selected="selected"{/if}>50</option>
                    <option value="100"{if $pager.itemsperpage eq 100} selected="selected"{/if}>100</option>
                </select>
            </div>

            <div class="z-formrow">
                <label for="MUTicket_searchterm">{gt text='Search for'}:</label>
                <input type="text" id="MUTicket_searchterm" name="searchterm" style="width: 150px" class="z-floatleft" style="margin-right: 10px" />
                <input type="button" id="MUTicket_gosearch" name="gosearch" value="{gt text='Filter'}" style="width: 80px" />
            </div>

            <div style="margin-left: 6em">
                {pager display='page' rowcount=$pager.numitems limit=$pager.itemsperpage posvar='pos' template='pagercss.tpl' maxpages='10'}
            </div>
            <input type="submit" id="MUTicket_submit" name="submitButton" value="{gt text='Change selection'}" />
            <input type="button" id="MUTicket_cancel" name="cancelButton" value="{gt text='Cancel'}" />
            <br />
        </fieldset>
    </div>
    </form>

    <script type="text/javascript">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            muticket.finder.onLoad();
        });
    /* ]]> */
    </script>

    {*
    <div class="muticketform">
        <fieldset>
            {modfunc modname='MUTicket' type='admin' func='edit'}
        </fieldset>
    </div>
    *}
</body>
</html>

{* Purpose of this template: Edit block for generic item list *}
<div class="z-formrow">
    <label for="MUTicket_objecttype">{gt text='Object type'}:</label>
    <select id="MUTicket_objecttype" name="objecttype" size="1">
        <option value="ticket"{if $objectType eq 'ticket'} selected="selected"{/if}>{gt text='Tickets'}</option>
        <option value="rating"{if $objectType eq 'rating'} selected="selected"{/if}>{gt text='Ratings'}</option>
        <option value="supporter"{if $objectType eq 'supporter'} selected="selected"{/if}>{gt text='Supporters'}</option>
        <option value="currentState"{if $objectType eq 'currentState'} selected="selected"{/if}>{gt text='Current states'}</option>
        <option value="label"{if $objectType eq 'label'} selected="selected"{/if}>{gt text='Labels'}</option>
    </select>
    <span class="z-sub z-formnote">{gt text='If you change this please save the block once to reload the parameters below.'}</span>
</div>

{if $properties ne null && is_array($properties)}
    {gt text='All' assign='lblDefault'}
    {nocache}
    {foreach key='propertyName' item='propertyId' from=$properties}
        <div class="z-formrow">
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
    <label for="MUTicket_sorting">{gt text='Sorting'}:</label>
    <select id="MUTicket_sorting" name="sorting">
        <option value="random"{if $sorting eq 'random'} selected="selected"{/if}>{gt text='Random'}</option>
        <option value="newest"{if $sorting eq 'newest'} selected="selected"{/if}>{gt text='Newest'}</option>
        <option value="alpha"{if $sorting eq 'default' || ($sorting != 'random' && $sorting != 'newest')} selected="selected"{/if}>{gt text='Default'}</option>
    </select>
</div>

<div class="z-formrow">
    <label for="MUTicket_amount">{gt text='Amount'}:</label>
    <input type="text" id="MUTicket_amount" name="amount" maxlength="2" size="10" value="{$amount|default:"5"}" />
</div>

<div class="z-formrow">
    <label for="MUTicket_template">{gt text='Template'}:</label>
    <select id="MUTicket_template" name="template">
        <option value="itemlist_display.tpl"{if $template eq 'itemlist_display.tpl'} selected="selected"{/if}>{gt text='Only item titles'}</option>
        <option value="itemlist_display_description.tpl"{if $template eq 'itemlist_display_description.tpl'} selected="selected"{/if}>{gt text='With description'}</option>
        <option value="custom"{if $template eq 'custom'} selected="selected"{/if}>{gt text='Custom template'}</option>
    </select>
</div>

<div id="customtemplatearea" class="z-formrow z-hide">
    <label for="MUTicket_customtemplate">{gt text='Custom template'}:</label>
    <input type="text" id="MUTicket__customtemplate" name="customtemplate" size="40" maxlength="80" value="{$customTemplate|default:''}" />
    <span class="z-sub z-formnote">{gt text='Example'}: <em>itemlist_{objecttype}_display.tpl</em></span>
</div>

<div class="z-formrow z-hide">
    <label for="MUTicket_filter">{gt text='Filter (expert option)'}:</label>
    <input type="text" id="MUTicket_filter" name="filter" size="40" value="{$filterValue|default:''}" />
    <span class="z-sub z-formnote">({gt text='Syntax examples'}: <kbd>name:like:foobar</kbd> {gt text='or'} <kbd>status:ne:3</kbd>)</span>
</div>

{pageaddvar name='javascript' value='prototype'}
<script type="text/javascript">
/* <![CDATA[ */
    function muticketToggleCustomTemplate() {
        if ($F('MUTicket_template') == 'custom') {
            $('customtemplatearea').removeClassName('z-hide');
        } else {
            $('customtemplatearea').addClassName('z-hide');
        }
    }

    document.observe('dom:loaded', function() {
        muticketToggleCustomTemplate();
        $('MUTicket_template').observe('change', function(e) {
            muticketToggleCustomTemplate();
        });
    });
/* ]]> */
</script>

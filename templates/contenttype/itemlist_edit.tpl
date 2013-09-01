{* Purpose of this template: edit view of generic item list content type *}

<div class="z-formrow">
    {gt text='Object type' domain='module_muticket' assign='objectTypeSelectorLabel'}
    {formlabel for='MUTicket_objecttype' text=$objectTypeSelectorLabel}
    {muticketObjectTypeSelector assign='allObjectTypes'}
    {formdropdownlist id='MUTicket_objecttype' dataField='objectType' group='data' mandatory=true items=$allObjectTypes}
    <span class="z-sub z-formnote">{gt text='If you change this please save the element once to reload the parameters below.' domain='module_muticket'}</span>
</div>

{formvolatile}
{if $properties ne null && is_array($properties)}
    {nocache}
    {foreach key='registryId' item='registryCid' from=$registries}
        {assign var='propName' value=''}
        {foreach key='propertyName' item='propertyId' from=$properties}
            {if $propertyId eq $registryId}
                {assign var='propName' value=$propertyName}
            {/if}
        {/foreach}
        <div class="z-formrow">
            {modapifunc modname='MUTicket' type='category' func='hasMultipleSelection' ot=$objectType registry=$propertyName assign='hasMultiSelection'}
            {gt text='Category' domain='module_muticket' assign='categorySelectorLabel'}
            {assign var='selectionMode' value='single'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' domain='module_muticket' assign='categorySelectorLabel'}
                {assign var='selectionMode' value='multiple'}
            {/if}
            {formlabel for="MUTicket_catids`$propertyName`" text=$categorySelectorLabel}
            {formdropdownlist id="MUTicket_catids`$propName`" items=$categories.$propName dataField="catids`$propName`" group='data' selectionMode=$selectionMode}
            <span class="z-sub z-formnote">{gt text='This is an optional filter.' domain='module_muticket'}</span>
        </div>
    {/foreach}
    {/nocache}
{/if}
{/formvolatile}

<div class="z-formrow">
    {gt text='Sorting' domain='module_muticket' assign='sortingLabel'}
    {formlabel text=$sortingLabel}
    <div>
        {formradiobutton id='MUTicket_srandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='module_muticket' assign='sortingRandomLabel'}
        {formlabel for='MUTicket_srandom' text=$sortingRandomLabel}
        {formradiobutton id='MUTicket_snewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='module_muticket' assign='sortingNewestLabel'}
        {formlabel for='MUTicket_snewest' text=$sortingNewestLabel}
        {formradiobutton id='MUTicket_sdefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='module_muticket' assign='sortingDefaultLabel'}
        {formlabel for='MUTicket_sdefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="z-formrow">
    {gt text='Amount' domain='module_muticket' assign='amountLabel'}
    {formlabel for='MUTicket_amount' text=$amountLabel}
    {formintinput id='MUTicket_amount' dataField='amount' group='data' mandatory=true maxLength=2}
</div>

<div class="z-formrow">
    {gt text='Template' domain='module_muticket' assign='templateLabel'}
    {formlabel for='MUTicket_template' text=$templateLabel}
    {muticketTemplateSelector assign='allTemplates'}
    {formdropdownlist id='MUTicket_template' dataField='template' group='data' mandatory=true items=$allTemplates}
</div>

<div id="customtemplatearea" class="z-formrow z-hide">
    {gt text='Custom template' domain='module_muticket' assign='customTemplateLabel'}
    {formlabel for='MUTicket_customtemplate' text=$customTemplateLabel}
    {formtextinput id='MUTicket_customtemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80}
    <span class="z-sub z-formnote">{gt text='Example' domain='module_muticket'}: <em>itemlist_[objecttype]_display.tpl</em></span>
</div>

<div class="z-formrow z-hide">
    {gt text='Filter (expert option)' domain='module_muticket' assign='filterLabel'}
    {formlabel for='MUTicket_filter' text=$filterLabel}
    {formtextinput id='MUTicket_filter' dataField='filter' group='data' mandatory=false maxLength=255}
    <span class="z-sub z-formnote">({gt text='Syntax examples' domain='module_muticket'}: <kbd>name:like:foobar</kbd> {gt text='or' domain='module_muticket'} <kbd>status:ne:3</kbd>)</span>
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

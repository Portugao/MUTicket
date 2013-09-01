{* Purpose of this template: edit view of specific item detail view content type *}

<div style="margin-left: 80px">
    <div class="z-formrow">
        {formlabel for='MUTicket_objecttype' __text='Object type'}
        {muticketObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='MUTicket_objecttype' dataField='objectType' group='data' mandatory=true items=$allObjectTypes}
        <span class="z-sub z-formnote">{gt text='If you change this please save the element once to reload the parameters below.'}</span>
    </div>
    <div{* class="z-formrow"*}>
        <p>{gt text='Please select your item here. You can resort the dropdown list and reduce it\'s entries by applying filters. On the right side you will see a preview of the selected entry.'}</p>
        {muticketItemSelector id='id' group='data' objectType=$objectType}
    </div>

    <div{* class="z-formrow"*}>
        {formradiobutton id='linkButton' value='link' dataField='displayMode' group='data' mandatory=1}
        {formlabel for='linkButton' __text='Link to object'}
        {formradiobutton id='embedButton' value='embed' dataField='displayMode' group='data' mandatory=1}
        {formlabel for='embedButton' __text='Embed object display'}
    </div>
</div>

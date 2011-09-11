{* Purpose of this template: edit view of generic item list content type *}

<div class="z-formrow">
    {formlabel for='MUTicket_objecttype' __text='Object type'}
    {muticketSelectorObjectTypes assign="allObjectTypes"}
    {formdropdownlist id='MUTicket_objecttype' dataField='objectType' group='data' mandatory=true items=$allObjectTypes}
</div>

<div class="z-formrow">
    {formlabel __text='Sorting'}
    <div>
        {formradiobutton id='MUTicket_srandom' value='random' dataField='sorting' group='data' mandatory=true}
        {formlabel for='MUTicket_srandom' __text='Random'}
        {formradiobutton id='MUTicket_snewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {formlabel for='MUTicket_snewest' __text='Newest'}
        {formradiobutton id='MUTicket_sdefault' value='default' dataField='sorting' group='data' mandatory=true}
        {formlabel for='MUTicket_sdefault' __text='Default'}
    </div>
</div>

<div class="z-formrow">
    {formlabel for='MUTicket_amount' __text='Amount'}
    {formtextinput id='MUTicket_amount' dataField='amount' group='data' mandatory=true maxLength=2}
</div>

<div class="z-formrow">
    {formlabel for='MUTicket_template' __text='Template File'}
    {muticketSelectorTemplates assign="allTemplates"}
    {formdropdownlist id='MUTicket_template' dataField='template' group='data' mandatory=true items=$allTemplates}
</div>

<div class="z-formrow">
    {formlabel for='MUTicket_filter' __text='Filter (expert option)'}
    {formtextinput id='MUTicket_filter' dataField='filter' group='data' mandatory=false maxLength=255}
    <div class="z-formnote">({gt text='Syntax examples'}: <kbd>name:like:foobar</kbd> {gt text='or'} <kbd>status:ne:3</kbd>)</div>
</div>

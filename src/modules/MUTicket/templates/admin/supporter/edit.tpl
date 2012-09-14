{* purpose of this template: build the Form to edit an instance of supporter *}
{include file='admin/header.tpl'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit supporter' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{elseif $mode eq 'create'}
    {gt text='Create supporter' assign='templateTitle'}
    {assign var='adminPageIcon' value='new'}
{else}
    {gt text='Edit supporter' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{/if}
<div class="muticket-supporter muticket-edit">
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type=$adminPageIcon size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>
{form cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}
    {formsetinitialfocus inputId='userid'}

    <fieldset>
        <legend>{gt text='Content'}</legend>
        <div class="z-formrow">
       {* {if $mode eq 'create'} *}
            {formlabel for='username' __text='Username' mandatorysym='1'}
            {*formtextinput group='supporter' id='username' mandatory=true readOnly=false __title='Input the username of the supporter' textMode='singleline' maxLength=100 cssClass='required'}
            {muticketValidationError id='username' class='required'*}
            {formdropdownlist group='supporter' id='username' mandatory=true __title='Select a user'}
       {* {else}
            {formtextinput group='supporter' id='username' mandatory=true readOnly=false __title='Input the username of the supporter' textMode='singleline' maxLength=100 cssClass='required'}
            {muticketValidationError id='username' class='required'} 
        {/if} *}
        </div>
        <div class="z-formrow">
            {formlabel for='supportcats' __text='Categories' mandatorysym='1'}
            {*formintinput group='supporter' id='supportcats' mandatory=true __title='Input the category of the variety' maxLength=4 cssClass='required validate-digits'}
            {muticketValidationError id='supportcats' class='required'}
            {muticketValidationError id='supportcats' class='validate-digits'} *}
            {formcheckboxlist selectedValue=$savedcats group='supporter' id='supportcats' selectionMode=multiple mandatory=true __title='Select a category'}
        </div>
        <div class="z-formrow">
            {formlabel for='state' __text='Is this supporter present?' mandatorysym='0'}
            {formcheckbox group='supporter' id='state' mame='state' readOnly=false __title='state' checked=checked}
        </div>
    </fieldset>

    {* {if $mode ne 'create'}
        {include file='admin/include_standardfields_edit.tpl' obj=$supporter}
    {/if} *}

    {* include display hooks *}
    {if $mode eq 'create'}
        {notifydisplayhooks eventname='muticket.ui_hooks.supporters.form_edit' id=null assign='hooks'}
    {else}
        {notifydisplayhooks eventname='muticket.ui_hooks.supporters.form_edit' id=$supporter.id assign='hooks'}
    {/if}
    {if is_array($hooks) && isset($hooks[0])}
        <fieldset>
            <legend>{gt text='Hooks'}</legend>
            {foreach key='hookName' item='hook' from=$hooks}
            <div class="z-formrow">
                {$hook}
            </div>
            {/foreach}
        </fieldset>
    {/if}

    {* include return control *}
    {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="z-formrow">
                {formlabel for='repeatcreation' __text='Create another item after save'}
                {formcheckbox group='supporter' id='repeatcreation' readOnly=false}
            </div>
        </fieldset>
    {/if}

    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {if $mode eq 'edit'}
        {formbutton id='btnUpdate' commandName='update' __text='Update supporter' class='z-bt-save'}
      {if !$inlineUsage}
        {gt text='Really delete this supporter?' assign="deleteConfirmMsg"}
        {formbutton id='btnDelete' commandName='delete' __text='Delete supporter' class='z-bt-delete z-btred' confirmMessage=$deleteConfirmMsg}
      {/if}
    {elseif $mode eq 'create'}
        {formbutton id='btnCreate' commandName='create' __text='Create supporter' class='z-bt-ok'}
    {else}
        {formbutton id='btnUpdate' commandName='update' __text='OK' class='z-bt-ok'}
    {/if}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
  {/muticketFormFrame}
{/form}

</div>
{include file='admin/footer.tpl'}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='deleteImageArray'}

<script type="text/javascript" charset="utf-8">
/* <![CDATA[ */
    var editImage = '<img src="{{$editImageArray.src}}" width="16" height="16" alt="" />';
    var removeImage = '<img src="{{$deleteImageArray.src}}" width="16" height="16" alt="" />';

    document.observe('dom:loaded', function() {

        muticketAddCommonValidationRules('supporter', '{{if $mode eq 'create'}}{{else}}{{$supporter.id}}{{/if}}');

        // observe button events instead of form submit
        var valid = new Validation('{{$__formid}}', {onSubmit: false, immediate: true, focusOnError: false});
        {{if $mode ne 'create'}}
            var result = valid.validate();
        {{/if}}

        $('{{if $mode eq 'create'}}btnCreate{{else}}btnUpdate{{/if}}').observe('click', function(event) {
            var result = valid.validate();
            if (!result) {
                // validation error, abort form submit
                Event.stop(event);
            } else {
                // hide form buttons to prevent double submits by accident
                $$('div.z-formbuttons input').each(function(btn) {
                    btn.hide();
                });
            }
            return result;
        });

        Zikula.UI.Tooltips($$('.muticketFormTooltips'));
    });

/* ]]> */
</script>

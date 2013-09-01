{* purpose of this template: build the Form to edit an instance of supporter *}
{include file='user/header.tpl'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit supporter' assign='templateTitle'}
{elseif $mode eq 'create'}
    {gt text='Create supporter' assign='templateTitle'}
{else}
    {gt text='Edit supporter' assign='templateTitle'}
{/if}
<div class="muticket-supporter muticket-edit">
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-frontendcontainer">
        <h2>{$templateTitle}</h2>
{form cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}

    {formsetinitialfocus inputId='username'}


    <fieldset>
        <legend>{gt text='Content'}</legend>
        
        <div class="z-formrow">
            {formlabel for='username' __text='Username' mandatorysym='1'}
            {formtextinput group='supporter' id='username' mandatory=true readOnly=false __title='Enter the username of the supporter' textMode='singleline' maxLength=100 cssClass='required' }
            {muticketValidationError id='username' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='supportcats' __text='Supportcats' mandatorysym='1'}
            {formtextinput group='supporter' id='supportcats' mandatory=true readOnly=false __title='Enter the supportcats of the supporter' textMode='singleline' maxLength=255 cssClass='required' }
            {muticketValidationError id='supportcats' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='state' __text='State' mandatorysym='1'}
            {formcheckbox group='supporter' id='state' readOnly=false __title='state ?' cssClass='required' }
            {muticketValidationError id='state' class='required'}
        </div>
    </fieldset>
    
    {if $mode ne 'create'}
        {include file='user/include_standardfields_edit.tpl' obj=$supporter}
    {/if}
    
    {* include display hooks *}
    {assign var='hookid' value=null}
    {if $mode ne 'create'}
        {assign var='hookid' value=$supporter.id}
    {/if}
    {notifydisplayhooks eventname='muticket.ui_hooks.supporters.form_edit' id=$hookId assign='hooks'}
    {if is_array($hooks) && count($hooks)}
        {foreach key='providerArea' item='hook' from=$hooks}
            <fieldset>
                {$hook}
            </fieldset>
        {/foreach}
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
    {foreach item='action' from=$actions}
        {assign var='actionIdCapital' value=$action.id|@ucwords}
        {gt text=$action.title assign='actionTitle'}
        {*gt text=$action.description assign='actionDescription'*}{* TODO: formbutton could support title attributes *}
        {if $action.id eq 'delete'}
            {gt text='Really delete this supporter?' assign='deleteConfirmMsg'}
            {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
        {else}
            {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
        {/if}
    {/foreach}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
    {/muticketFormFrame}
{/form}

    </div>
</div>
{include file='user/footer.tpl'}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='deleteImageArray'}


<script type="text/javascript">
/* <![CDATA[ */

    var formButtons, formValidator;

    function handleFormButton (event) {
        var result = formValidator.validate();
        if (!result) {
            // validation error, abort form submit
            Event.stop(event);
        } else {
            // hide form buttons to prevent double submits by accident
            formButtons.each(function (btn) {
                btn.addClassName('z-hide');
            });
        }

        return result;
    }

    document.observe('dom:loaded', function() {

        muticketAddCommonValidationRules('supporter', '{{if $mode ne 'create'}}{{$supporter.id}}{{/if}}');
        {{* observe validation on button events instead of form submit to exclude the cancel command *}}
        formValidator = new Validation('{{$__formid}}', {onSubmit: false, immediate: true, focusOnError: false});
        {{if $mode ne 'create'}}
            var result = formValidator.validate();
        {{/if}}

        formButtons = $('{{$__formid}}').select('div.z-formbuttons input');

        formButtons.each(function (elem) {
            if (elem.id != 'btnCancel') {
                elem.observe('click', handleFormButton);
            }
        });

        Zikula.UI.Tooltips($$('.muticketFormTooltips'));
    });

/* ]]> */
</script>

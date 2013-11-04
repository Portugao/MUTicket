{* purpose of this template: build the Form to edit an instance of label *}
{include file='user/header.tpl'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit label' assign='templateTitle'}
{elseif $mode eq 'create'}
    {gt text='Create label' assign='templateTitle'}
{else}
    {gt text='Edit label' assign='templateTitle'}
{/if}
<div class="muticket-label muticket-edit">
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-frontendcontainer">
        <h2>{$templateTitle}</h2>
{form cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}

    {formsetinitialfocus inputId='name'}


    <fieldset>
        <legend>{gt text='Content'}</legend>
        
        <div class="z-formrow">
            {formlabel for='name' __text='Name' mandatorysym='1'}
            {formtextinput group='label' id='name' mandatory=true readOnly=false __title='Enter the name of the label' textMode='singleline' maxLength=255 cssClass='required' }
            {muticketValidationError id='name' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='labelColor' __text='Label color' mandatorysym='1'}
            {muticketColourInput group='label' id='labelColor' mandatory=true __title='Choose the label color of the label' cssClass='required validate-nospace' }
            {muticketValidationError id='labelColor' class='required'}
            {muticketValidationError id='labelColor' class='validate-nospace'}
            {muticketValidationError id='labelColor' class='validate-htmlcolour'}
        </div>
    </fieldset>
    
    {if $mode ne 'create'}
        {include file='user/include_standardfields_edit.tpl' obj=$label}
    {/if}
    
    {* include display hooks *}
    {assign var='hookid' value=null}
    {if $mode ne 'create'}
        {assign var='hookid' value=$label.id}
    {/if}
    {notifydisplayhooks eventname='muticket.ui_hooks.labels.form_edit' id=$hookId assign='hooks'}
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
                {formcheckbox group='label' id='repeatcreation' readOnly=false}
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
            {gt text='Really delete this label?' assign='deleteConfirmMsg'}
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

        muticketAddCommonValidationRules('label', '{{if $mode ne 'create'}}{{$label.id}}{{/if}}');
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
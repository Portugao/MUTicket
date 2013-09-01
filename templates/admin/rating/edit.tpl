{* purpose of this template: build the Form to edit an instance of rating *}
{include file='admin/header.tpl'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit rating' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{elseif $mode eq 'create'}
    {gt text='Create rating' assign='templateTitle'}
    {assign var='adminPageIcon' value='new'}
{else}
    {gt text='Edit rating' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{/if}
<div class="muticket-rating muticket-edit">
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-admin-content-pagetitle">
        {icon type=$adminPageIcon size='small' alt=$templateTitle}
        <h3>{$templateTitle}</h3>
    </div>
{form cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}

    {formsetinitialfocus inputId='ratingvalue'}


    <fieldset>
        <legend>{gt text='Content'}</legend>
        
        <div class="z-formrow">
            {formlabel for='ratingvalue' __text='Ratingvalue' mandatorysym='1'}
            {formintinput group='rating' id='ratingvalue' mandatory=true __title='Enter the ratingvalue of the rating' maxLength=2 cssClass='required validate-digits' }
            {muticketValidationError id='ratingvalue' class='required'}
            {muticketValidationError id='ratingvalue' class='validate-digits'}
        </div>
    </fieldset>
    
    {include file='admin/ticket/include_selectEditOne.tpl' group='rating' alias='ticket' aliasReverse='rating' mandatory=false idPrefix='muticketRating_Ticket' linkingItem=$rating displayMode='dropdown' allowEditing=true}
    {if $mode ne 'create'}
        {include file='admin/include_standardfields_edit.tpl' obj=$rating}
    {/if}
    
    {* include display hooks *}
    {assign var='hookid' value=null}
    {if $mode ne 'create'}
        {assign var='hookid' value=$rating.id}
    {/if}
    {notifydisplayhooks eventname='muticket.ui_hooks.ratings.form_edit' id=$hookId assign='hooks'}
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
                {formcheckbox group='rating' id='repeatcreation' readOnly=false}
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
            {gt text='Really delete this rating?' assign='deleteConfirmMsg'}
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
{include file='admin/footer.tpl'}

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

        muticketAddCommonValidationRules('rating', '{{if $mode ne 'create'}}{{$rating.id}}{{/if}}');
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

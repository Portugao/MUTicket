{* purpose of this template: build the Form to edit an instance of current state *}
{include file='admin/header.tpl'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit current state' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{elseif $mode eq 'create'}
    {gt text='Create current state' assign='templateTitle'}
    {assign var='adminPageIcon' value='new'}
{else}
    {gt text='Edit current state' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{/if}
<div class="muticket-currentstate muticket-edit">
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-admin-content-pagetitle">
        {icon type=$adminPageIcon size='small' alt=$templateTitle}
        <h3>{$templateTitle}</h3>
    </div>
{form enctype='multipart/form-data' cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}

    {formsetinitialfocus inputId='title'}


    <fieldset>
        <legend>{gt text='Content'}</legend>
        
        <div class="z-formrow">
            {formlabel for='title' __text='Title' mandatorysym='1'}
            {formtextinput group='currentstate' id='title' mandatory=true readOnly=false __title='Enter the title of the current state' textMode='singleline' maxLength=255 cssClass='required' }
            {muticketValidationError id='title' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='description' __text='Description'}
            {formtextinput group='currentstate' id='description' mandatory=false __title='Enter the description of the current state' textMode='multiline' rows='6' cols='50' cssClass='' }
        </div>
        
        <div class="z-formrow">
            {formlabel for='uploadIcon' __text='Upload icon'}<br />{* break required for Google Chrome *}
            {formuploadinput group='currentstate' id='uploadIcon' mandatory=false readOnly=false cssClass=' validate-upload' }
            <p class="z-formnote"><a id="resetUploadIconVal" href="javascript:void(0);" class="z-hide">{gt text='Reset to empty value'}</a></p>
            
                <div class="z-formnote">{gt text='Allowed file extensions:'} <span id="fileextensionsuploadIcon">gif, jpeg, jpg, png</span></div>
            <div class="z-formnote">{gt text='Allowed file size:'} {'102400'|muticketGetFileSize:'':false:false}</div>
            {if $mode ne 'create'}
                {if $currentstate.uploadIcon ne ''}
                    <div class="z-formnote">
                        {gt text='Current file'}:
                        <a href="{$currentstate.uploadIconFullPathUrl}" title="{$currentstate.title|replace:"\"":""}"{if $currentstate.uploadIconMeta.isImage} rel="imageviewer[currentstate]"{/if}>
                        {if $currentstate.uploadIconMeta.isImage}
                            {thumb image=$currentstate.uploadIconFullPath objectid="currentState-`$currentstate.id`" preset=$currentStateThumbPresetUploadIcon tag=true img_alt=$currentstate.title}
                        {else}
                            {gt text='Download'} ({$currentstate.uploadIconMeta.size|muticketGetFileSize:$currentstate.uploadIconFullPath:false:false})
                        {/if}
                        </a>
                    </div>
                    <div class="z-formnote">
                        {formcheckbox group='currentstate' id='uploadIconDeleteFile' readOnly=false __title='Delete upload icon ?'}
                        {formlabel for='uploadIconDeleteFile' __text='Delete existing file'}
                    </div>
                {/if}
            {/if}
            {muticketValidationError id='uploadIcon' class='validate-upload'}
        </div>
    </fieldset>
    
    {if $mode ne 'create'}
        {include file='admin/include_standardfields_edit.tpl' obj=$currentstate}
    {/if}
    
    {* include display hooks *}
    {assign var='hookid' value=null}
    {if $mode ne 'create'}
        {assign var='hookid' value=$currentstate.id}
    {/if}
    {notifydisplayhooks eventname='muticket.ui_hooks.currentstates.form_edit' id=$hookId assign='hooks'}
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
                {formcheckbox group='currentstate' id='repeatcreation' readOnly=false}
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
            {gt text='Really delete this current state?' assign='deleteConfirmMsg'}
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

        muticketAddCommonValidationRules('currentState', '{{if $mode ne 'create'}}{{$currentstate.id}}{{/if}}');
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
        muticketInitUploadField('uploadIcon');
    });

/* ]]> */
</script>

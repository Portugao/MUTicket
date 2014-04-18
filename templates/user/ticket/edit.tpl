{* purpose of this template: build the Form to edit an instance of ticket *}
{if $func ne 'display'}
{include file='user/header.tpl'}
{/if}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit ticket' assign='templateTitle'}
{elseif $mode eq 'create' && $func ne 'display'}
    {gt text='Create ticket' assign='templateTitle'}
{elseif $mode eq 'create' && $func eq 'display'}
    {gt text='Answer to ticket' assign='templateTitle'}
{else}
    {gt text='Edit ticket' assign='templateTitle'}
{/if}
<div class="muticket-ticket muticket-edit">
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>
{muticketform enctype='multipart/form-data' cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}
    {formsetinitialfocus inputId='title'}

<div class="z-panels" id="MUTicket_panel">
      {*  <h3 id="z-panel-header-fields" class="z-panel-header z-panel-indicator z-pointer">{gt text='Fields'}</h3> *}
        <div class="z-panel-content z-panel-active" style="overflow: visible">
            <fieldset>
                <legend>{gt text='Content'}</legend>                            
                <div class="z-formrow">
                {if $func ne 'display'} 
                    {formlabel for='title' __text='Title' mandatorysym='1'}
                    {formtextinput group='ticket' id='title' mandatory=true readOnly=false __title='Enter the title of the ticket' textMode='singleline' maxLength=255 cssClass='required' }
                    {muticketValidationError id='title' class='required'}
                {/if} 
                </div>                             
                <div class="z-formrow">
                    {formlabel for='text' __text='Text' mandatorysym='1'}
                    {formtextinput group='ticket' id='text' mandatory=true __title='Enter the text of the ticket' textMode='multiline' rows='6' cols='50' cssClass='required' }
                    {muticketValidationError id='text' class='required'}
                </div>          
                <div class="z-formrow">
                    {formlabel for='images' __text='Images'}<br />{* break required for Google Chrome *}
                    {formuploadinput group='ticket' id='images' mandatory=false readOnly=false cssClass=' validate-upload' }
                    <p class="z-formnote"><a id="resetImagesVal" href="javascript:void(0);" class="z-hide">{gt text='Reset to empty value'}</a></p>
                    
                        <div class="z-formnote">{gt text='Allowed file extensions:'} <span id="fileextensionsimages">gif, jpeg, jpg, png</span></div>
                    {if $mode ne 'create'}
                        {if $ticket.images ne ''}
                            <div class="z-formnote">
                                {gt text='Current file'}:
                                <a href="{$ticket.imagesFullPathUrl}" title="{$ticket.title|replace:"\"":""}"{if $ticket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
                                {if $ticket.imagesMeta.isImage}
                                    {thumb image=$ticket.imagesFullPath objectid="ticket-`$ticket.id`" preset=$ticketThumbPresetImages tag=true img_alt=$ticket.title}
                                {else}
                                    {gt text='Download'} ({$ticket.imagesMeta.size|muticketGetFileSize:$ticket.imagesFullPath:false:false})
                                {/if}
                                </a>
                            </div>
                            <div class="z-formnote">
                                {formcheckbox group='ticket' id='imagesDeleteFile' readOnly=false __title='Delete images ?'}
                                {formlabel for='imagesDeleteFile' __text='Delete existing file'}
                            </div>
                        {/if}
                    {/if}
                    {muticketValidationError id='images' class='validate-upload'}
                </div>         
                <div class="z-formrow">
                    {formlabel for='files' __text='Files'}<br />{* break required for Google Chrome *}
                    {formuploadinput group='ticket' id='files' mandatory=false readOnly=false cssClass=' validate-upload' }
                    <p class="z-formnote"><a id="resetFilesVal" href="javascript:void(0);" class="z-hide">{gt text='Reset to empty value'}</a></p>     
                        <div class="z-formnote">{gt text='Allowed file extensions:'} <span id="fileextensionsfiles">pdf, doc, odt</span></div>
                    {if $mode ne 'create'}
                        {if $ticket.files ne ''}
                            <div class="z-formnote">
                                {gt text='Current file'}:
                                <a href="{$ticket.filesFullPathUrl}" title="{$ticket.title|replace:"\"":""}"{if $ticket.filesMeta.isImage} rel="imageviewer[ticket]"{/if}>
                                {if $ticket.filesMeta.isImage}
                                    {thumb image=$ticket.filesFullPath objectid="ticket-`$ticket.id`" preset=$ticketThumbPresetFiles tag=true img_alt=$ticket.title}
                                {else}
                                    {gt text='Download'} ({$ticket.filesMeta.size|muticketGetFileSize:$ticket.filesFullPath:false:false})
                                {/if}
                                </a>
                            </div>
                            <div class="z-formnote">
                                {formcheckbox group='ticket' id='filesDeleteFile' readOnly=false __title='Delete files ?'}
                                {formlabel for='filesDeleteFile' __text='Delete existing file'}
                            </div>
                        {/if}
                    {/if}
                    {muticketValidationError id='files' class='validate-upload'}
                </div>
                
                <div class="z-formrow muticket_form_hidden">
                    {formlabel for='state' __text='State'}
                    {formcheckbox group='ticket' id='state' readOnly=false __title='state ?' cssClass='' }
                </div>
                
                <div class="z-formrow muticket_form_hidden">
                    {formlabel for='rated' __text='Rated'}
                    {formcheckbox group='ticket' id='rated' readOnly=false __title='rated ?' cssClass='' }
                </div>
                <input type="hidden" id="owner" value="1" />             
                <div class="z-formrow muticket_form_hidden">
                    {formlabel for='dueDate' __text='Due date'}
                    {if $mode ne 'create'}
                        {formdateinput group='ticket' id='dueDate' mandatory=false __title='Enter the due date of the ticket' includeTime=true cssClass='' }
                    {else}
                        {formdateinput group='ticket' id='dueDate' mandatory=false __title='Enter the due date of the ticket' includeTime=true defaultValue='now' cssClass='' }
                    {/if}                 
                </div>            
                <div class="z-formrow muticket_form_hidden">
                    {gt text='If you do not want to give a concrete date, you can enter a string like "end of november 2013".' assign='toolTip'}
                    {formlabel for='dueText' __text='Due text' class='muticketFormTooltips' title=$toolTip}
                    {formtextinput group='ticket' id='dueText' mandatory=false readOnly=false __title='Enter the due text of the ticket' textMode='singleline' maxLength=255 cssClass='' }
                </div>
                <input type="hidden" id="currentState" value="0" />  
            </fieldset>
        </div>
        {if $func ne 'display'}
        {include file='user/include_categories_edit.tpl' obj=$ticket groupName='ticketObj' panel=false}
        {/if}
        {* {include file='user/ticket/include_selectOne.tpl' group='ticket' alias='parent' aliasReverse='children' mandatory=false idPrefix='muticketTicket_Parent' linkingItem=$ticket panel=true displayMode='dropdown' allowEditing=false}
        {include file='user/currentState/include_selectOne.tpl' group='ticket' alias='currentState' aliasReverse='ticket' mandatory=false idPrefix='muticketTicket_CurrentState' linkingItem=$ticket panel=true displayMode='dropdown' allowEditing=false} *}
        {if $mode ne 'create'}
            {include file='user/include_standardfields_edit.tpl' obj=$ticket panel=true}
        {/if}
        
        {* include display hooks *}
        {assign var='hookid' value=null}
        {if $mode ne 'create'}
            {assign var='hookid' value=$ticket.id}
        {/if}
        {notifydisplayhooks eventname='muticket.ui_hooks.tickets.form_edit' id=$hookId assign='hooks'}
        {if is_array($hooks) && count($hooks)}
            {foreach key='providerArea' item='hook' from=$hooks}
                <h3 class="hook z-panel-header z-panel-indicator z-pointer">{$providerArea}</h3>
                <fieldset class="hook z-panel-content" style="display: none">{$hook}</div>
                    {$hook}
                </fieldset>
            {/foreach}
        {/if} 
    </fieldset>
    
 {*   <input type="hidden" id="state" name="state" value="1">
    <input type="hidden" id="rated" name="rated" value="0"> *}
    
    {if $func eq 'edit'}
    	<input type="hidden" id="muticketTicket_ParentItemList" name="muticketTicket_ParentItemList" value="0">
    	<input type="hidden" id="muticketTicket_ParentMode" name="muticketTicket_ParentMode" value="0">    
    {/if}
    {if $func eq 'display'}
    	<input type="hidden" id="muticketTicket_ParentItemList" name="muticketTicket_ParentItemList" value="{$ticketid}">
    	<input type="hidden" id="muticketTicket_ParentMode" name="muticketTicket_ParentMode" value="0">
    {/if}
    {* {include file='user/ticket/include_selectOne.tpl' relItem=$ticket aliasName='parent' idPrefix='muticketTicket_Parent'} *}
    {* include display hooks *}
    {if $mode eq 'create'}
        {notifydisplayhooks eventname='muticket.ui_hooks.tickets.form_edit' id=null assign='hooks'}
    {else}
        {notifydisplayhooks eventname='muticket.ui_hooks.tickets.form_edit' id=$ticket.id assign='hooks'}
    {/if}
    {if is_array($hooks) && count($hooks) > 0}
        <fieldset>
            <legend>{gt text='Hooks'}</legend>
            
            {foreach key='hookName' item='hook' from=$hooks}
            <div class="z-formrow">
                {$hook}
            </div>
            {/foreach}
        </fieldset>
    {/if}

    {* We don't need this *} 
    {* include return control *}
    {* {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="z-formrow">
                {formlabel for='repeatcreation' __text='Create another item after save'}
                {formcheckbox group='ticket' id='repeatcreation' readOnly=false}
            </div>
        </fieldset>
    {/if} *}

        {* include possible submit actions *}
        <div class="z-buttons z-formbuttons">
        {if $func eq 'display'}
        {formbutton id="btnSubmit" commandName='create' __text='Submit' class='z-bt-save'}
        {else}
        {foreach item='action' from=$actions}
            {assign var='actionIdCapital' value=$action.id|@ucwords}
            {gt text=$action.title assign='actionTitle'}
            {*gt text=$action.description assign='actionDescription'*}{* TODO: formbutton could support title attributes *}
           {* {if $action.id eq 'delete'}
                {gt text='Really delete this ticket?' assign='deleteConfirmMsg'}
                {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
            {else} *}
                {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
           {* {/if} *}
        {/foreach}
        {/if}
           {* {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'} *}
        </div>
  {/muticketFormFrame}
{/muticketform}

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

        muticketAddCommonValidationRules('ticket', '{{if $mode ne 'create'}}{{$ticket.id}}{{/if}}');
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
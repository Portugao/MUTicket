{* purpose of this template: build the Form to edit an instance of ticket *}
{if $editkind eq 'none'}
{include file='admin/header.tpl'}
{/if}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit ticket' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{elseif $mode eq 'create'}
    {gt text='Create ticket' assign='templateTitle'}
    {assign var='adminPageIcon' value='new'}
{else}
    {gt text='Edit ticket' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{/if}
<div class="muticket-ticket muticket-edit">
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-admin-content-pagetitle">
        {icon type=$adminPageIcon size='small' alt=$templateTitle}
        <h3>{$templateTitle}</h3>
    </div>
{form enctype='multipart/form-data' cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}

    {formsetinitialfocus inputId='title'}
    <div class="z-panels" id="MUTicket_panel">
        {if $editkind ne 'label'} 
        <h3 id="z-panel-header-fields" class="z-panel-header z-panel-indicator z-pointer">{gt text='Fields'}</h3>
        {/if}
        <div class="z-panel-content z-panel-active" style="overflow: visible">
            {if $editkind ne 'label'}    
            <fieldset>
            {/if}
                <legend>{gt text='Content'}</legend>
                {if $editkind eq 'none'}
                <div class="z-formrow">
                    {formlabel for='title' __text='Title'}
                    {formtextinput group='ticket' id='title' mandatory=false readOnly=false __title='Enter the title of the ticket' textMode='singleline' maxLength=255 cssClass='' }
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
                <div class="z-formrow">
                    {formlabel for='state' __text='State'}
                    {formcheckbox group='ticket' id='state' readOnly=false __title='state ?' cssClass='' }
                </div>            
                <div class="z-formrow">
                    {formlabel for='rated' __text='Rated'}
                    {formcheckbox group='ticket' id='rated' readOnly=false __title='rated ?' cssClass='' }
                </div>              
                <div class="z-formrow">
                    {gt text='If you want to give this ticket to another supporter you should have a look if he is present!' assign='toolTip'}
                    {formlabel for='owner' __text='Owner' class='muticketFormTooltips' title=$toolTip}
                    {muticketUserInput group='ticket' id='owner' mandatory=false readOnly=false __title='Enter a part of the user name to search'}
                    {if $mode ne 'create' && $ticket.owner && !$inlineUsage}
                        {checkpermissionblock component='Users::' instance='::' level='ACCESS_ADMIN'}
                        <div class="z-formnote"><a href="{modurl modname='Users' type='admin' func='modify' userid=$ticket.owner}" title="{gt text='Switch to the user administration'}">{gt text='Manage user'}</a></div>
                        {/checkpermissionblock}
                    {/if}
                </div>               
                <div class="z-formrow">
                    {formlabel for='dueDate' __text='Due date'}
                    {if $mode ne 'create'}
                        {formdateinput group='ticket' id='dueDate' mandatory=false __title='Enter the due date of the ticket' includeTime=true cssClass='' }
                    {else}
                        {formdateinput group='ticket' id='dueDate' mandatory=false __title='Enter the due date of the ticket' includeTime=true defaultValue='now' cssClass='' }
                    {/if}               
                </div>           
                <div class="z-formrow">
                    {gt text='If you don"t want to give a concrete date, you can enter a string like "end of november 2013".' assign='toolTip'}
                    {formlabel for='dueText' __text='Due text' class='muticketFormTooltips' title=$toolTip}
                    {formtextinput group='ticket' id='dueText' mandatory=false readOnly=false __title='Enter the due text of the ticket' textMode='singleline' maxLength=255 cssClass='' }
                </div>
                {/if} 
                {if $editkind eq 'dialog'}         
                <div class="z-formrow">
                    {formlabel for='currentState' __text='Current state' mandatorysym='1'}
                    {formdropdownlist group='ticket' id='currentState' mandatory=true __title='Choose the current state' selectionMode='single'}
                </div>
                <div class="z-formrow">
                    {formlabel for='sendStateMessage' __text='Send Message?' mandatorysym='0'}
                    {formcheckbox group='message' id='sendStateMessage' mandatory=false __title='Choose if an email should be send'}
                </div>                
                {/if}
            {if $editkind ne 'label'}    
            </fieldset>
            {/if}
        </div>
        {if $editkind eq 'none'}
        {include file='admin/include_categories_edit.tpl' obj=$ticket groupName='ticketObj' panel=true}
        {include file='admin/ticket/include_selectOne.tpl' group='ticket' alias='parent' aliasReverse='children' mandatory=false idPrefix='muticketTicket_Parent' linkingItem=$ticket panel=true displayMode='dropdown' allowEditing=false}
        {/if} 
        {if $editkind ne 'dialog'} 
        {if $editkind eq 'none'}       
        {include file='admin/label/include_selectEditMany.tpl' group='ticket' alias='labelticket' aliasReverse='ticketlabel' mandatory=false idPrefix='muticketTicket_Labelticket' linkingItem=$ticket panel=true displayMode='dropdown' allowEditing=true}
        {else}
        {include file='admin/label/include_selectEditMany.tpl' group='ticket' alias='labelticket' aliasReverse='ticketlabel' mandatory=false idPrefix='muticketTicket_Labelticket' linkingItem=$ticket panel=false displayMode='dropdown' allowEditing=true}        
        {/if}
        {/if}
                {if $editkind eq 'label'}         
                <div class="z-formrow">
                    {formlabel for='sendLabelMessage' __text='Send Message?' mandatorysym='0'}
                    {formcheckbox group='message' id='sendLabelMessage' mandatory=false __title='Choose if an email should be send'}
                </div>                
                {/if}
        {if $editkind eq 'none'}
        {if $mode ne 'create'}
            {include file='admin/include_standardfields_edit.tpl' obj=$ticket panel=true}
        {/if}
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
        
        {* include return control *}
        {if $mode eq 'create'}
            <fieldset>
                <legend>{gt text='Return control'}</legend>
                <div class="z-formrow">
                    {formlabel for='repeatcreation' __text='Create another item after save'}
                    {formcheckbox group='ticket' id='repeatcreation' readOnly=false}
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
                {if $editkind eq 'none'}
                {gt text='Really delete this ticket?' assign='deleteConfirmMsg'}
                {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
                {/if}
            {else}
                {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
            {/if}
        {/foreach}
            {if $editkind eq 'none'}
            {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}
            {/if}
        </div>
    </div>
    {/muticketFormFrame}
{/form}

</div>
{if $editkind eq 'none'}
{include file='admin/footer.tpl'}
{/if}

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
        // initialise auto completion for user fields
        muticketInitUserField('owner', 'getTicketOwnerUsers');

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

        var panel = new Zikula.UI.Panels('MUTicket_panel', {
            headerSelector: 'h3',
            headerClassName: 'z-panel-header z-panel-indicator',
            contentClassName: 'z-panel-content',
            active: $('z-panel-header-fields')
        });

        Zikula.UI.Tooltips($$('.muticketFormTooltips'));
        muticketInitUploadField('images');
        muticketInitUploadField('files');
    });

/* ]]> */
</script>

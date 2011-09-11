{* purpose of this template: build the Form to edit an instance of ticket *}
{include file='user/header.tpl'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit ticket' assign='templateTitle'}
{elseif $mode eq 'create'}
    {gt text='Create ticket' assign='templateTitle'}
{else}
    {gt text='Edit ticket' assign='templateTitle'}
{/if}
<div class="muticket-ticket muticket-edit">
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>
{form enctype='multipart/form-data' cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}
    {formsetinitialfocus inputId='title'}

    <fieldset>
        <legend>{gt text='Content'}</legend>
        <div class="z-formrow">
            {formlabel for='title' __text='Title'}
            {formtextinput group='ticket' id='title' mandatory=false readOnly=false __title='Input the title of the ticket' textMode='singleline' maxLength=255 cssClass=''}
        </div>
        <div class="z-formrow">
            {formlabel for='text' __text='Text' mandatorysym='1'}
            {formtextinput group='ticket' id='text' mandatory=true __title='Input the text of the ticket' textMode='multiline' rows='6' cols='50' cssClass='required'}
            {muticketValidationError id='text' class='required'}
        </div>
        <div class="z-formrow">
            {if $mode eq 'create'}
                {formlabel for='images' __text='Images'}<br />{* break required for Google Chrome *}
            {else}
                {formlabel for='images' __text='Images'}<br />{* break required for Google Chrome *}
            {/if}
            {formuploadinput group='ticket' id='images' mandatory=false readOnly=false cssClass=''}

            <div class="z-formnote">{gt text='Allowed file extensions:'} gif, jpeg, jpg, png</div>
            {if $mode ne 'create'}
                {if $ticket.images ne ''}
                  <div class="z-formnote">
                      {gt text='Current file'}:
                      <a href="{$ticket.imagesFullPathUrl}" title="{$ticket.title|replace:"\"":""}"{if $ticket.imagesMeta.isImage} rel="imageviewer[ticket]"{/if}>
                      {if $ticket.imagesMeta.isImage}
                          <img src="{$ticket.images|muticketImageThumb:$ticket.imagesFullPath:80:50}" width="80" height="50" alt="{$ticket.title|replace:"\"":""}" />
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
        </div>
        <div class="z-formrow">
            {if $mode eq 'create'}
                {formlabel for='files' __text='Files'}<br />{* break required for Google Chrome *}
            {else}
                {formlabel for='files' __text='Files'}<br />{* break required for Google Chrome *}
            {/if}
            {formuploadinput group='ticket' id='files' mandatory=false readOnly=false cssClass=''}

            <div class="z-formnote">{gt text='Allowed file extensions:'} pdf, doc, odt</div>
            {if $mode ne 'create'}
                {if $ticket.files ne ''}
                  <div class="z-formnote">
                      {gt text='Current file'}:
                      <a href="{$ticket.filesFullPathUrl}" title="{$ticket.title|replace:"\"":""}"{if $ticket.filesMeta.isImage} rel="imageviewer[ticket]"{/if}>
                      {if $ticket.filesMeta.isImage}
                          <img src="{$ticket.files|muticketImageThumb:$ticket.filesFullPath:80:50}" width="80" height="50" alt="{$ticket.title|replace:"\"":""}" />
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
        </div>
        <div class="z-formrow">
            {formlabel for='state' __text='State' mandatorysym='1'}
            {formtextinput group='ticket' id='state' mandatory=true readOnly=false __title='Input the state of the ticket' textMode='singleline' maxLength=50 cssClass='required'}
            {muticketValidationError id='state' class='required'}
        </div>
        <div class="z-formrow">
            {formlabel for='t_rating' __text='T_rating' mandatorysym='1'}
            {formintinput group='ticket' id='t_rating' mandatory=true __title='Input the t_rating of the ticket' maxLength=1 cssClass='required validate-digits'}
            {muticketValidationError id='t_rating' class='required'}
            {muticketValidationError id='t_rating' class='validate-digits'}
        </div>
        <div class="z-formrow">
            {formlabel for='rated' __text='Rated' mandatorysym='1'}
            {formintinput group='ticket' id='rated' mandatory=true __title='Input the rated of the ticket' maxLength=1 cssClass='required validate-digits'}
            {muticketValidationError id='rated' class='required'}
            {muticketValidationError id='rated' class='validate-digits'}
        </div>
    </fieldset>

    {include file='user/include_categories_edit.tpl' obj=$ticket groupName='ticketObj'}
    {if $mode ne 'create'}
        {include file='user/include_standardfields_edit.tpl' obj=$ticket}
    {/if}
    {include file='user/ticket/include_selectOne.tpl' relItem=$ticket aliasName='parent' idPrefix='muticketTicket_Parent'}

    {* include display hooks *}
    {if $mode eq 'create'}
        {notifydisplayhooks eventname='muticket.ui_hooks.tickets.form_edit' id=null assign='hooks'}
    {else}
        {notifydisplayhooks eventname='muticket.ui_hooks.tickets.form_edit' id=$ticket.id assign='hooks'}
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
                {formcheckbox group='ticket' id='repeatcreation' readOnly=false}
            </div>
        </fieldset>
    {/if}

    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {if $mode eq 'edit'}
        {formbutton id='btnUpdate' commandName='update' __text='Update ticket' class='z-bt-save'}
      {if !$inlineUsage}
        {gt text='Really delete this ticket?' assign="deleteConfirmMsg"}
        {formbutton id='btnDelete' commandName='delete' __text='Delete ticket' class='z-bt-delete z-btred' confirmMessage=$deleteConfirmMsg}
      {/if}
    {elseif $mode eq 'create'}
        {formbutton id='btnCreate' commandName='create' __text='Create ticket' class='z-bt-ok'}
    {else}
        {formbutton id='btnUpdate' commandName='update' __text='OK' class='z-bt-ok'}
    {/if}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
  {/muticketFormFrame}
{/form}

</div>
</div>
{include file='user/footer.tpl'}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='deleteImageArray'}

<script type="text/javascript" charset="utf-8">
/* <![CDATA[ */
    var editImage = '<img src="{{$editImageArray.src}}" width="16" height="16" alt="" />';
    var removeImage = '<img src="{{$deleteImageArray.src}}" width="16" height="16" alt="" />';
    var relationHandler = new Array();
    var newItem = new Object();
    newItem['ot'] = 'ticket';
    newItem['alias'] = 'Parent';
    newItem['prefix'] = 'muticketTicket_ParentSelectorDoNew';
    newItem['acInstance'] = null;
    newItem['windowInstance'] = null;
    relationHandler.push(newItem);

    document.observe('dom:loaded', function() {
        muticketInitRelationItemsForm('ticket', 'muticketTicket_Parent', false);

        muticketAddCommonValidationRules('ticket', '{{if $mode eq 'create'}}{{else}}{{$ticket.id}}{{/if}}');

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

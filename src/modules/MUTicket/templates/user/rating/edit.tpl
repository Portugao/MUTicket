{* purpose of this template: build the Form to edit an instance of rating *}
{include file='user/header.tpl'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit rating' assign='templateTitle'}
{elseif $mode eq 'create'}
    {gt text='Create rating' assign='templateTitle'}
{else}
    {gt text='Edit rating' assign='templateTitle'}
{/if}
<div class="muticket-rating muticket-edit">
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>
{form cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}
    {formsetinitialfocus inputId='ratingvalue'}

    <fieldset>
        <legend>{gt text='Content'}</legend>
        <div class="z-formrow">
            {formlabel for='ratingvalue' __text='Ratingvalue' mandatorysym='1'}
            {formfloatinput group='rating' id='ratingvalue' mandatory=true __title='Input the ratingvalue of the rating' maxLength=15 cssClass='required validate-number'}
            {muticketValidationError id='ratingvalue' class='required'}
            {muticketValidationError id='ratingvalue' class='validate-number'}
        </div>
    </fieldset>

    {if $mode ne 'create'}
        {include file='user/include_standardfields_edit.tpl' obj=$rating}
    {/if}

    {* include display hooks *}
    {if $mode eq 'create'}
        {notifydisplayhooks eventname='muticket.ui_hooks.ratings.form_edit' id=null assign='hooks'}
    {else}
        {notifydisplayhooks eventname='muticket.ui_hooks.ratings.form_edit' id=$rating.id assign='hooks'}
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
                {formcheckbox group='rating' id='repeatcreation' readOnly=false}
            </div>
        </fieldset>
    {/if}

    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {if $mode eq 'edit'}
        {formbutton id='btnUpdate' commandName='update' __text='Update rating' class='z-bt-save'}
      {if !$inlineUsage}
        {gt text='Really delete this rating?' assign="deleteConfirmMsg"}
        {formbutton id='btnDelete' commandName='delete' __text='Delete rating' class='z-bt-delete z-btred' confirmMessage=$deleteConfirmMsg}
      {/if}
    {elseif $mode eq 'create'}
        {formbutton id='btnCreate' commandName='create' __text='Create rating' class='z-bt-ok'}
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

    document.observe('dom:loaded', function() {

        muticketAddCommonValidationRules('rating', '{{if $mode eq 'create'}}{{else}}{{$rating.id}}{{/if}}');

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

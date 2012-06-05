{* purpose of this template: build the Form to edit an instance of rating *}
{* {pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_editFunctions.js'} *}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket_validation.js'}

<div class="muticket-rating muticket-edit">

<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>
    {muticketratingform cssClass='z-form' ticket=$ticket}
    {* add validation summary and a <div> element for styling the form *}
    {muticketFormFrame}
    {formsetinitialfocus inputId='ratingvalue'}
    <fieldset>
        <div class="z-formrow">
            {foreach from=$rating item=item}
            <div class="muticket_rating_value">
            {formradiobutton group='rating' id='ratingvalue' value=$item.value dataField='ratingvalue' mandatory=true} {formlabel text=$item.text for=$item.name}
            </div>
            {/foreach}
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

    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {if $mode eq 'edit'}
        {formbutton id='btnUpdate' commandName='update' __text='Update rating' class='z-bt-save'}
      {if !$inlineUsage}
        {gt text='Really delete this rating?' assign='deleteConfirmMsg'}
        {formbutton id='btnDelete' commandName='delete' __text='Delete rating' class='z-bt-delete z-btred' confirmMessage=$deleteConfirmMsg}
      {/if}
    {elseif $mode eq 'create'}
        {formbutton id='btnCreate' commandName='create' __text='Vote' class='z-bt-ok'}
    {else}
        {formbutton id='btnUpdate' commandName='update' __text='OK' class='z-bt-ok'}
    {/if}
    </div>
  {/muticketFormFrame}
{/muticketratingform}

</div>
</div>

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

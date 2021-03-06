{* purpose of this template: module configuration *}
{include file='admin/header.tpl'}
<div class="muticket-config">
{gt text='Settings' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='config' size='small' __alt='Settings'}
    <h3>{$templateTitle}</h3>
</div>
    {form cssClass='z-form'}

        {* add validation summary and a <div> element for styling the form *}
        {muticketFormFrame}
            {formsetinitialfocus inputId='supportergroup'}
            {formtabbedpanelset}
            {gt text='General' assign='tabTitle'}
            {formtabbedpanel title=$tabTitle}
            <fieldset>
                <legend>{gt text='Here you can manage all basic settings for this application.'}</legend>
                <div class="z-formrow">
                    {formlabel for='supportergroup' __text='Supportergroup'}
                    {formdropdownlist id='supportergroup' group='config' maxLength=255 width=20em __title='Input this setting.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='ratingAllowed' __text='Allow rating?'}
                    {formdropdownlist id='ratingAllowed' group='config' maxLength=255 width=20em __title='Input this setting.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='fileSize' __text='Allowed filesize?'}
                    {formintinput id='fileSize' group='config' maxLength=20 width=20em __title='Input this setting.'}
                    <div class="z-informationmsg z-formnote">{gt text="102400 = 100 KB."}</div>
                </div>                             
                <div class="z-formrow">
                    {formlabel for='supporterTicktes' __text='Supporter group may create tickets?'}
                    {formcheckbox id='supporterTickets' group='config' __title='Activate or deactivate this option.'}              
                </div> 
            </fieldset>
            <fieldset>
                <legend>{gt text='Do you want to delete all existing supporters?'}</legend>
                <div class="z-formrow">
                    {formlabel for='supporters' __text='Delete existing supporters?'}
                    {formcheckbox id='delete_supporter' name='delete_supporter' group='supporter'}              
                </div>           
            </fieldset>
            
              {/formtabbedpanel}
            {gt text='Extended' assign='tabTitle'}
            {formtabbedpanel title=$tabTitle}
            <fieldset>
                <legend>{gt text='Extended'}</legend>
            
                <div class="z-formrow">
                    {formlabel for='messageNewOwner' __text='Message new owner'}
                    {formtextinput id='messageNewOwner' group='config' maxLength=255 __title='Enter the message new owner.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='messageDueDate' __text='Message due date'}
                    {formtextinput id='messageDueDate' group='config' maxLength=255 __title='Enter the message due date.'}
                </div>
            </fieldset>
            {/formtabbedpanel}
            {/formtabbedpanelset}
            
            <div class="z-buttons z-formbuttons">
                {formbutton commandName='save' __text='Update configuration' class='z-bt-save'}
                {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
            </div>
        {/muticketFormFrame}
    {/form}
</div>
{include file='admin/footer.tpl'}

{* purpose of this template: supporters delete confirmation view in admin area *}
{include file='admin/header.tpl'}
<div class="muticket-supporter muticket-delete">
{gt text='Delete supporter' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='delete' size='small' __alt='Delete'}
    <h3>{$templateTitle}</h3>
</div>

<p class="z-warningmsg">{gt text='Do you really want to delete this supporter ?'}</p>

<form class="z-form" action="{modurl modname='MUTicket' type='admin' func='delete' ot='supporter' id=$supporter.id}" method="post">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" id="confirmation" name="confirmation" value="1" />
        <fieldset>
            <legend>{gt text='Confirmation prompt'}</legend>
            <div class="z-buttons z-formbuttons">
                {gt text='Delete' assign='deleteTitle'}
                {button src='14_layer_deletelayer.png' set='icons/small' text=$deleteTitle title=$deleteTitle class='z-btred'}
                <a href="{modurl modname='MUTicket' type='admin' func='view' ot='supporter'}">{icon type='cancel' size='small' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </fieldset>

        {notifydisplayhooks eventname='muticket.ui_hooks.supporters.form_delete' id="`$supporter.id`" assign='hooks'}
        {foreach key='providerArea' item='hook' from=$hooks}
        <fieldset>
            <legend>{$hookName}</legend>
            {$hook}
        </fieldset>
        {/foreach}
    </div>
</form>
</div>
{include file='admin/footer.tpl'}

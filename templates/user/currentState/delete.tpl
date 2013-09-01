{* purpose of this template: current states delete confirmation view in user area *}
{include file='user/header.tpl'}
<div class="muticket-currentstate muticket-delete">
{gt text='Delete current state' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>

<p class="z-warningmsg">{gt text='Do you really want to delete this current state ?'}</p>

<form class="z-form" action="{modurl modname='MUTicket' type='user' func='delete' ot='currentState' id=$currentState.id}" method="post">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" id="confirmation" name="confirmation" value="1" />
        <fieldset>
            <legend>{gt text='Confirmation prompt'}</legend>
            <div class="z-buttons z-formbuttons">
                {gt text='Delete' assign='deleteTitle'}
                {button src='14_layer_deletelayer.png' set='icons/small' text=$deleteTitle title=$deleteTitle class='z-btred'}
                <a href="{modurl modname='MUTicket' type='user' func='view' ot='currentState'}">{icon type='cancel' size='small' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </fieldset>

        {notifydisplayhooks eventname='muticket.ui_hooks.currentstates.form_delete' id="`$currentState.id`" assign='hooks'}
        {foreach key='providerArea' item='hook' from=$hooks}
        <fieldset>
            <legend>{$hookName}</legend>
            {$hook}
        </fieldset>
        {/foreach}
    </div>
</form>
</div>
</div>
{include file='user/footer.tpl'}

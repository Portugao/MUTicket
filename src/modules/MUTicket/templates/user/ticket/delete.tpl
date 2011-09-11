{* purpose of this template: tickets delete confirmation view in user area *}
{include file='user/header.tpl'}
<div class="muticket-ticket muticket-delete">
{gt text='Delete ticket' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>

    <p class="z-warningmsg">{gt text='Do you really want to delete this ticket ?'}</p>

    <form class="z-form" action="{modurl modname='MUTicket' type='user' func='delete' ot='ticket' id=$ticket.id}" method="post">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" id="confirmation" name="confirmation" value="1" />
            <fieldset>
                <legend>{gt text='Confirmation prompt'}</legend>
                <div class="z-buttons z-formbuttons">
                    {gt text='Delete' assign='deleteTitle'}
                    {button src='14_layer_deletelayer.png' set='icons/small' text=$deleteTitle title=$deleteTitle class='z-btred'}
                    <a href="{modurl modname='MUTicket' type='user' func='view' ot='ticket'}">{icon type='cancel' size='small' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
                </div>
            </fieldset>

            {notifydisplayhooks eventname='muticket.ui_hooks.tickets.form_delete' id="`$ticket.id`" assign='hooks'}
            {foreach from=$hooks key='hookName' item='hook'}
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

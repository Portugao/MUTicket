{* purpose of this template: inclusion template for managing related ticket in user area *}
{if !isset($displayMode)}
    {assign var='displayMode' value='dropdown'}
{/if}
{if !isset($allowEditing)}
    {assign var='allowEditing' value=false}
{/if}
{if isset($panel) && $panel eq true}
    <h3 class="ticket z-panel-header z-panel-indicator z-pointer">{gt text='Ticket'}</h3>
    <fieldset class="ticket z-panel-content" style="display: none">
{else}
    <fieldset class="ticket">
{/if}
    <legend>{gt text='Ticket'}</legend>
    <div class="z-formrow">
    {if $displayMode eq 'dropdown'}
        {formlabel for=$alias __text='Choose ticket'}
        {muticketRelationSelectorList group=$group id=$alias aliasReverse=$aliasReverse mandatory=$mandatory __title='Choose the ticket' selectionMode='single' objectType='ticket' linkingItem=$linkingItem}
    {elseif $displayMode eq 'autocomplete'}
        {assign var='createLink' value=''}
        {if $allowEditing eq true}
            {modurl modname='MUTicket' type='user' func='edit' ot='ticket' forcelongurl=true assign='createLink'}
        {/if}
        {muticketRelationSelectorAutoComplete group=$group id=$alias aliasReverse=$aliasReverse mandatory=$mandatory __title='Choose the ticket' selectionMode='single' objectType='ticket' linkingItem=$linkingItem idPrefix=$idPrefix createLink=$createLink selectedEntityName='ticket' withImage=true}
        <div class="muticketRelationLeftSide">
            {if isset($linkingItem.$alias)}
                {include file='user/ticket/include_selectItemListOne.tpl'  item=$linkingItem.$alias}
            {else}
                {include file='user/ticket/include_selectItemListOne.tpl' }
            {/if}
        </div>
        <br class="z-clearer" />
    {/if}
    </div>
</fieldset>

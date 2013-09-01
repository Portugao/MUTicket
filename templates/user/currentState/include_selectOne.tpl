{* purpose of this template: inclusion template for managing related current state in user area *}
{if !isset($displayMode)}
    {assign var='displayMode' value='dropdown'}
{/if}
{if !isset($allowEditing)}
    {assign var='allowEditing' value=false}
{/if}
{if isset($panel) && $panel eq true}
    <h3 class="currentstate z-panel-header z-panel-indicator z-pointer">{gt text='Current state'}</h3>
    <fieldset class="currentstate z-panel-content" style="display: none">
{else}
    <fieldset class="currentstate">
{/if}
    <legend>{gt text='Current state'}</legend>
    <div class="z-formrow">
    {if $displayMode eq 'dropdown'}
        {formlabel for=$alias __text='Choose current state'}
        {muticketRelationSelectorList group=$group id=$alias aliasReverse=$aliasReverse mandatory=$mandatory __title='Choose the current state' selectionMode='single' objectType='currentState' linkingItem=$linkingItem}
    {elseif $displayMode eq 'autocomplete'}
        {assign var='createLink' value=''}
        {if $allowEditing eq true}
            {modurl modname='MUTicket' type='user' func='edit' ot='currentState' forcelongurl=true assign='createLink'}
        {/if}
        {muticketRelationSelectorAutoComplete group=$group id=$alias aliasReverse=$aliasReverse mandatory=$mandatory __title='Choose the current state' selectionMode='single' objectType='currentState' linkingItem=$linkingItem idPrefix=$idPrefix createLink=$createLink selectedEntityName='current state' withImage=false}
        <div class="muticketRelationLeftSide">
            {if isset($linkingItem.$alias)}
                {include file='user/currentState/include_selectItemListOne.tpl'  item=$linkingItem.$alias}
            {else}
                {include file='user/currentState/include_selectItemListOne.tpl' }
            {/if}
        </div>
        <br class="z-clearer" />
    {/if}
    </div>
</fieldset>

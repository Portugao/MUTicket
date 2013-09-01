{* purpose of this template: inclusion template for managing related labels in admin area *}
{if !isset($displayMode)}
    {assign var='displayMode' value='dropdown'}
{/if}
{if !isset($allowEditing)}
    {assign var='allowEditing' value=false}
{/if}
{if isset($panel) && $panel eq true}
    <h3 class="labels z-panel-header z-panel-indicator z-pointer">{gt text='Labels'}</h3>
    <fieldset class="labels z-panel-content" style="display: none">
{else}
    <fieldset class="labels">
{/if}
    <legend>{gt text='Labels'}</legend>
    <div class="z-formrow">
    {if $displayMode eq 'dropdown'}
        {formlabel for=$alias __text='Choose labels'}
        {muticketRelationSelectorList group=$group id=$alias aliasReverse=$aliasReverse mandatory=$mandatory __title='Choose the labels' selectionMode='multiple' objectType='label' linkingItem=$linkingItem}
    {elseif $displayMode eq 'autocomplete'}
        {assign var='createLink' value=''}
        {if $allowEditing eq true}
            {modurl modname='MUTicket' type='admin' func='edit' ot='label' assign='createLink'}
        {/if}
        {muticketRelationSelectorAutoComplete group=$group id=$alias aliasReverse=$aliasReverse mandatory=$mandatory __title='Choose the labels' selectionMode='multiple' objectType='label' linkingItem=$linkingItem idPrefix=$idPrefix createLink=$createLink selectedEntityName='labels' withImage=false}
        <div class="muticketRelationLeftSide">
            {if isset($linkingItem.$alias)}
                {include file='admin/label/include_selectEditItemListMany.tpl'  items=$linkingItem.$alias}
            {else}
                {include file='admin/label/include_selectEditItemListMany.tpl' }
            {/if}
        </div>
        <br class="z-clearer" />
    {/if}
    </div>
</fieldset>

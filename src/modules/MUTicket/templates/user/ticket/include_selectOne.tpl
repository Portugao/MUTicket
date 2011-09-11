{* purpose of this template: inclusion template for managing related Tickets in user area *}
<fieldset>
    <legend>{gt text='Ticket'}</legend>
    <div class="z-formrow">
    <div class="muticketRelationRightSide">
        <a id="{$idPrefix}AddLink" href="javascript:void(0);" style="display: none">{gt text='Select ticket'}</a>
        <div id="{$idPrefix}AddFields">
            <label for="{$idPrefix}Selector">{gt text='Find ticket'}</label>
            <br />
            <input type="text" name="{$idPrefix}Selector" id="{$idPrefix}Selector" value="" />
            <input type="hidden" name="{$idPrefix}Scope" id="{$idPrefix}Scope" value="0" />
            {img src='indicator_circle.gif' modname='core' set='ajax' alt='' id="`$idPrefix`Indicator" style='display: none'}
            <div id="{$idPrefix}SelectorChoices" class="muticketAutoCompleteWithImage"></div>
            <input type="button" id="{$idPrefix}SelectorDoCancel" name="{$idPrefix}SelectorDoCancel" value="{gt text='Cancel'}" class="z-button muticketInlineButton" />
        </div>
        <noscript><p>{gt text='This function requires JavaScript activated!'}</p></noscript>
    </div>
    <div class="muticketRelationLeftSide">
        {if isset($userSelection.$aliasName) && $userSelection.$aliasName ne ''}
            {* the user has submitted something *}
            {include file='user/ticket/include_selectItemListOne.tpl' item=$userSelection.$aliasName}
        {elseif $mode ne 'create' || isset($relItem.$aliasName)}
            {include file='user/ticket/include_selectItemListOne.tpl' item=$relItem.$aliasName}
        {else}
            {include file='user/ticket/include_selectItemListOne.tpl'}
        {/if}
    </div>
    <br style="clear: both" />
    </div>
</fieldset>

{* Purpose of this template: Edit block for generic item list *}

<div class="z-formrow">
    <label for="MUTicket_objecttype">{gt text='Object type'}:</label>
    <select id="MUTicket_objecttype" name="objecttype" size="1">
        <option value="ticket"{if $objectType eq 'ticket'} selected="selected"{/if}>{gt text='Tickets'}</option>
        <option value="rating"{if $objectType eq 'rating'} selected="selected"{/if}>{gt text='Ratings'}</option>
        <option value="supporter"{if $objectType eq 'supporter'} selected="selected"{/if}>{gt text='Supporters'}</option>
    </select>
</div>

<div class="z-formrow">
    <label for="MUTicket_sorting">{gt text='Sorting'}:</label>
    <select id="MUTicket_sorting" name="sorting">
        <option value="random"{if $sorting eq 'random'} selected="selected"{/if}>{gt text='Random'}</option>
        <option value="newest"{if $sorting eq 'newest'} selected="selected"{/if}>{gt text='Newest'}</option>
        <option value="alpha"{if $sorting eq 'default' || ($sorting != 'random' && $sorting != 'newest')} selected="selected"{/if}>{gt text='Default'}</option>
    </select>
</div>

<div class="z-formrow">
    <label for="MUTicket_amount">{gt text='Amount'}:</label>
    <input type="text" id="MUTicket_amount" name="amount" size="10" value="{$amount|default:"5"}" />
</div>

<div class="z-formrow">
    <label for="MUTicket_template">{gt text='Template File'}:</label>
    <select id="MUTicket_template" name="template">
        <option value="itemlist_display.tpl"{if $template eq 'itemlist_display.tpl'} selected="selected"{/if}>{gt text='Only item titles'}</option>
        <option value="itemlist_display_description.tpl"{if $template eq 'itemlist_display_description.tpl'} selected="selected"{/if}>{gt text='With description'}</option>
    </select>
</div>

<div class="z-formrow">
    <label for="MUTicket_filter">{gt text='Filter (expert option)'}:</label>
    <input type="text" id="MUTicket_filter" name="filter" size="40" value="{$filterValue|default:""}" />
    <div class="z-formnote">({gt text='Syntax examples'}: <kbd>name:like:foobar</kbd> {gt text='or'} <kbd>status:ne:3</kbd>)</div>
</div>

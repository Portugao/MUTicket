{* Purpose of this template: Display search options *}
<input type="hidden" id="active_muticket" name="active[MUTicket]" value="1" checked="checked" />
<div>
    <input type="checkbox" id="active_muticket_tickets" name="search_muticket_types[]" value="ticket"{if $active_ticket} checked="checked"{/if} />
    <label for="active_muticket_tickets">{gt text='Tickets' domain='module_muticket'}</label>
</div>
<div>
    <input type="checkbox" id="active_muticket_ratings" name="search_muticket_types[]" value="rating"{if $active_rating} checked="checked"{/if} />
    <label for="active_muticket_ratings">{gt text='Ratings' domain='module_muticket'}</label>
</div>
<div>
    <input type="checkbox" id="active_muticket_supporters" name="search_muticket_types[]" value="supporter"{if $active_supporter} checked="checked"{/if} />
    <label for="active_muticket_supporters">{gt text='Supporters' domain='module_muticket'}</label>
</div>
<div>
    <input type="checkbox" id="active_muticket_currentstates" name="search_muticket_types[]" value="currentState"{if $active_currentState} checked="checked"{/if} />
    <label for="active_muticket_currentstates">{gt text='Current states' domain='module_muticket'}</label>
</div>
<div>
    <input type="checkbox" id="active_muticket_labels" name="search_muticket_types[]" value="label"{if $active_label} checked="checked"{/if} />
    <label for="active_muticket_labels">{gt text='Labels' domain='module_muticket'}</label>
</div>

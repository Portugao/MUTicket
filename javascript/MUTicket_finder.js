'use strict';

var currentMUTicketEditor = null;
var currentMUTicketInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPopupAttributes() {
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;
    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a Xinha button.
 */
function MUTicketFinderXinha(editor, muticketURL) {
    var popupAttributes;

    // Save editor for access in selector window
    currentMUTicketEditor = editor;

    popupAttributes = getPopupAttributes();
    window.open(muticketURL, '', popupAttributes);
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function MUTicketFinderCKEditor(editor, muticketURL) {
    // Save editor for access in selector window
    currentMUTicketEditor = editor;

    editor.popup(
        Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=MUTicket&type=external&func=finder&editor=ckeditor',
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}



var muticket = {};

muticket.finder = {};

muticket.finder.onLoad = function (baseId, selectedId) {
    $$('div.categoryselector select').invoke('observe', 'change', muticket.finder.onParamChanged);
    $('MUTicket_sort').observe('change', muticket.finder.onParamChanged);
    $('MUTicket_sortdir').observe('change', muticket.finder.onParamChanged);
    $('MUTicket_pagesize').observe('change', muticket.finder.onParamChanged);
    $('MUTicket_gosearch').observe('click', muticket.finder.onParamChanged)
                           .observe('keypress', muticket.finder.onParamChanged);
    $('MUTicket_submit').addClassName('z-hide');
    $('MUTicket_cancel').observe('click', muticket.finder.handleCancel);
};

muticket.finder.onParamChanged = function () {
    $('selectorForm').submit();
};

muticket.finder.handleCancel = function () {
    var editor, w;

    editor = $F('editorName');
    if (editor === 'xinha') {
        w = parent.window;
        window.close();
        w.focus();
    } else if (editor === 'tinymce') {
        muticketClosePopup();
    } else if (editor === 'ckeditor') {
        muticketClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function getPasteSnippet(mode, itemId) {
    var itemUrl, itemTitle, itemDescription, pasteMode;

    itemUrl = $F('url' + itemId);
    itemTitle = $F('title' + itemId);
    itemDescription = $F('desc' + itemId);
    pasteMode = $F('MUTicket_pasteas');

    if (pasteMode === '2' || pasteMode !== '1') {
        return itemId;
    }

    // return link to item
    if (mode === 'url') {
        // plugin mode
        return itemUrl;
    } else {
        // editor mode
        return '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
}


// User clicks on "select item" button
muticket.finder.selectItem = function (itemId) {
    var editor, html;

    editor = $F('editorName');
    if (editor === 'xinha') {
        if (window.opener.currentMUTicketEditor !== null) {
            html = getPasteSnippet('html', itemId);

            window.opener.currentMUTicketEditor.focusEditor();
            window.opener.currentMUTicketEditor.insertHTML(html);
        } else {
            html = getPasteSnippet('url', itemId);
            var currentInput = window.opener.currentMUTicketInput;

            if (currentInput.tagName === 'INPUT') {
                // Simply overwrite value of input elements
                currentInput.value = html;
            } else if (currentInput.tagName === 'TEXTAREA') {
                // Try to paste into textarea - technique depends on environment
                if (typeof document.selection !== 'undefined') {
                    // IE: Move focus to textarea (which fortunately keeps its current selection) and overwrite selection
                    currentInput.focus();
                    window.opener.document.selection.createRange().text = html;
                } else if (typeof currentInput.selectionStart !== 'undefined') {
                    // Firefox: Get start and end points of selection and create new value based on old value
                    var startPos = currentInput.selectionStart;
                    var endPos = currentInput.selectionEnd;
                    currentInput.value = currentInput.value.substring(0, startPos)
                                        + html
                                        + currentInput.value.substring(endPos, currentInput.value.length);
                } else {
                    // Others: just append to the current value
                    currentInput.value += html;
                }
            }
        }
    } else if (editor === 'tinymce') {
        html = getPasteSnippet('html', itemId);
        window.opener.tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if (editor === 'ckeditor') {
        /** to be done*/
    } else {
        alert('Insert into Editor: ' + editor);
    }
    muticketClosePopup();
};


function muticketClosePopup() {
    window.opener.focus();
    window.close();
}




//=============================================================================
// MUTicket item selector for Forms
//=============================================================================

muticket.itemSelector = {};
muticket.itemSelector.items = {};
muticket.itemSelector.baseId = 0;
muticket.itemSelector.selectedId = 0;

muticket.itemSelector.onLoad = function (baseId, selectedId) {
    muticket.itemSelector.baseId = baseId;
    muticket.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    $('MUTicket_objecttype').observe('change', muticket.itemSelector.onParamChanged);

    if ($(baseId + '_catidMain') != undefined) {
        $(baseId + '_catidMain').observe('change', muticket.itemSelector.onParamChanged);
    }
    $(baseId + '_id').observe('change', muticket.itemSelector.onItemChanged);
    $(baseId + '_sort').observe('change', muticket.itemSelector.onParamChanged);
    $(baseId + '_sortdir').observe('change', muticket.itemSelector.onParamChanged);
    $('MUTicket_gosearch').observe('click', muticket.itemSelector.onParamChanged)
                           .observe('keypress', muticket.itemSelector.onParamChanged);

    muticket.itemSelector.getItemList();
};

muticket.itemSelector.onParamChanged = function () {
    $('ajax_indicator').removeClassName('z-hide');

    muticket.itemSelector.getItemList();
};

muticket.itemSelector.getItemList = function () {
    var baseId, pars, request;

    baseId = muticket.itemSelector.baseId;
    pars = 'ot=' + baseId + '&';
    if ($(baseId + '_catidMain') != undefined) {
        pars += 'catidMain=' + $F(baseId + '_catidMain') + '&';
    }
    pars += 'sort=' + $F(baseId + '_sort') + '&' +
            'sortdir=' + $F(baseId + '_sortdir') + '&' +
            'searchterm=' + $F(baseId + '_searchterm');

    request = new Zikula.Ajax.Request('ajax.php?module=MUTicket&func=getItemListFinder', {
        method: 'post',
        parameters: pars,
        onFailure: function(req) {
            Zikula.showajaxerror(req.getMessage());
        },
        onSuccess: function(req) {
            var baseId;
            baseId = muticket.itemSelector.baseId;
            muticket.itemSelector.items[baseId] = req.getData();
            $('ajax_indicator').addClassName('z-hide');
            muticket.itemSelector.updateItemDropdownEntries();
            muticket.itemSelector.updatePreview();
        }
    });
};

muticket.itemSelector.updateItemDropdownEntries = function () {
    var baseId, itemSelector, items, i, item;

    baseId = muticket.itemSelector.baseId;
    itemSelector = $(baseId + '_id');
    itemSelector.length = 0;

    items = muticket.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.options[i] = new Option(item.title, item.id, false);
    }

    if (muticket.itemSelector.selectedId > 0) {
        $(baseId + '_id').value = muticket.itemSelector.selectedId;
    }
};

muticket.itemSelector.updatePreview = function () {
    var baseId, items, selectedElement, i;

    baseId = muticket.itemSelector.baseId;
    items = muticket.itemSelector.items[baseId];

    $(baseId + '_previewcontainer').addClassName('z-hide');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (muticket.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id === muticket.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (selectedElement !== null) {
        $(baseId + '_previewcontainer').update(window.atob(selectedElement.previewInfo))
                                       .removeClassName('z-hide');
    }
};

muticket.itemSelector.onItemChanged = function () {
    var baseId, itemSelector, preview;

    baseId = muticket.itemSelector.baseId;
    itemSelector = $(baseId + '_id');
    preview = window.atob(muticket.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    $(baseId + '_previewcontainer').update(preview);
    muticket.itemSelector.selectedId = $F(baseId + '_id');
};

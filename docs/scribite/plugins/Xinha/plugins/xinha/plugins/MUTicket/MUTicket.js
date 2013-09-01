// MUTicket plugin for Xinha
// developed by Michael Ueberschaer
//
// requires MUTicket module (http://www.webdesign-in-bremen.com)
//
// Distributed under the same terms as xinha itself.
// This notice MUST stay intact for use (see license.txt).

'use strict';

function MUTicket(editor) {
    var cfg, self;

    this.editor = editor;
    cfg = editor.config;
    self = this;

    cfg.registerButton({
        id       : 'MUTicket',
        tooltip  : 'Insert MUTicket object',
     // image    : _editor_url + 'plugins/MUTicket/img/ed_MUTicket.gif',
        image    : '/images/icons/extrasmall/favorites.png',
        textMode : false,
        action   : function (editor) {
            var url = Zikula.Config.baseURL + 'index.php'/*Zikula.Config.entrypoint*/ + '?module=MUTicket&type=external&func=finder&editor=xinha';
            MUTicketFinderXinha(editor, url);
        }
    });
    cfg.addToolbarElement('MUTicket', 'insertimage', 1);
}

MUTicket._pluginInfo = {
    name          : 'MUTicket for xinha',
    version       : '1.1.0',
    developer     : 'Michael Ueberschaer',
    developer_url : 'http://www.webdesign-in-bremen.com',
    sponsor       : 'ModuleStudio 0.6.1',
    sponsor_url   : 'http://modulestudio.de',
    license       : 'htmlArea'
};

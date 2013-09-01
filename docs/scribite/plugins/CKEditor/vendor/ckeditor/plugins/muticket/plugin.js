CKEDITOR.plugins.add('MUTicket', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertMUTicket', {
            exec: function (editor) {
                var url = Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=MUTicket&type=external&func=finder&editor=ckeditor';
                // call method in MUTicket_Finder.js and also give current editor
                MUTicketFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('muticket', {
            label: 'Insert MUTicket object',
            command: 'insertMUTicket',
         // icon: this.path + 'images/ed_muticket.png'
            icon: '/images/icons/extrasmall/favorites.png'
        });
    }
});

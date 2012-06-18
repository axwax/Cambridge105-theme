// JavaScript Document
(function() {
    tinymce.create('tinymce.plugins.box', {
        init : function(ed, url) {
            ed.addButton('box', {
                title : 'Box',
                image : url+'/../images/box.png',
                onclick : function() {
                     ed.selection.setContent('[box]' + ed.selection.getContent() + '[/box]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('box', tinymce.plugins.box);
})();
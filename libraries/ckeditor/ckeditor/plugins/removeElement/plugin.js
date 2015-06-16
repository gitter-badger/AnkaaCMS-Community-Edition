CKEDITOR.plugins.add( 'removeElement', {
    icons: 'noimage.png',
    init: function( editor ) {
        editor.addCommand( 'remove', {
            exec: function( editor ) {
            	console.log(editor.name);
            	document.getElementById(editor.name).remove();
            	document.getElementById(editor.id).remove();
            }
        });
        editor.ui.addButton( 'removeElement', {
            label: 'Remove Element',
            command: 'remove',
            toolbar: 'links'
        });
    }
});
/**
* Cancel
*
* Es necesario indicar esto en la instancia para que noChangesData esté definido

CKEDITOR.on( 'instanceCreated', function( event ) {
	editor.on( 'focus', function( e){
    editor.noChangesData = editor.getData();
	})
});
*/
CKEDITOR.plugins.add( 'cancel', {
	init: function( editor )	{
		editor.addCommand( 'cancel', {
				exec : function( editor )	{
				  if( editor.noChangesData) {
  					editor.setData( editor.noChangesData);
				  }
				  editor.closeOnBlur = true;
				  editor.focusManager.blur( false);
				  editor.element.focusPrevious();
				}
			});
		editor.ui.addButton( 'Cancel', {
			label: 'Cancel',
			command: 'cancel',
			icon: this.path + 'images/cancel.png'
		});
	}
});
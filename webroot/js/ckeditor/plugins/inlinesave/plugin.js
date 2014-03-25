CKEDITOR.plugins.add( 'inlinesave',
{
	init: function( editor )
	{
		editor.addCommand( 'inlinesave',
			{
				exec : function( editor )
				{
          console.log( editor)
					addData();
					
					function addData() {
						var data = editor.getData();
            jQuery.ajax({
             type: "POST",
             url: editor.element.data( 'url'),
             data: {
               id: editor.element.data( 'id'),
               value: data,
               field: editor.element.data( 'field')
             }
            })
            .done(function (data, textStatus, jqXHR) {
              editor.closeOnBlur = true;
              editor.focusManager.blur( true);
              editor.element.focusPrevious();
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
             alert("Error saving content. [" + jqXHR.responseText + "]");
            });   
					} 

				}
			});
		editor.ui.addButton( 'Inlinesave',
		{
			label: 'Save',
			command: 'inlinesave',
			icon: this.path + 'images/inlinesave.png'
		} );
	}
} );
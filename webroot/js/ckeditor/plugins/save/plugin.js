(function() {
  console.log( 'yes');
	var saveCmd = { modes:{wysiwyg:1,source:1 },
		readOnly: 1,

	exec: function( editor ) {
            var data = editor.getData();
            jQuery.post(editor.config.saveSubmitURL,
                        {text: data},
                        function(response){

                        });
		}
	};

	var pluginName = 'save';

	// Register a plugin named "save".
	CKEDITOR.plugins.add( pluginName, {
		lang: 'af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en-au,en-ca,en-gb,en,eo,es,et,eu,fa,fi,fo,fr-ca,fr,gl,gu,he,hi,hr,hu,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt-br,pt,ro,ru,sk,sl,sr-latn,sr,sv,th,tr,ug,uk,vi,zh-cn,zh', // %REMOVE_LINE_CORE%
		icons: 'save', // %REMOVE_LINE_CORE%
		init: function( editor ) {
			var command = editor.addCommand( pluginName, saveCmd );
			//command.modes = { wysiwyg: !!( editor.element.$.form ) };

			editor.ui.addButton( 'Save', {
				label: editor.lang.save.toolbar,
				command: pluginName,
				toolbar: 'clipboard,50'
			});
		}
	});
})();
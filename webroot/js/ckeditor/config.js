/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.extraPlugins = 'inlinesave,cancel,simpleuploads,colordialog,colorbutton,font,oembed,widget,justify,smiley';
	config.filebrowserUploadUrl = '/management/fileupload/upload';
};

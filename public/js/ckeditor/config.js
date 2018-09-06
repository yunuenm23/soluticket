/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'es-mx';
	// config.uiColor = '#101010';
	config.skin = 'moono-dark';
	config.extraPlugins = "iframe,youtube,iframedialog,dialog,image2,imageresponsive";
	config.youtube_responsive = true;
	config.youtube_controls = true;
	config.allowedContent = true;

};

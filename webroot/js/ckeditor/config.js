/**
 * Croogo Links CKEditor custom config file.
 * Make sure the clink plugin is loaded from the clinks path,
 * then replace the default link plugin with clink.
 */
CKEDITOR.plugins.addExternal('clink',
	Croogo.basePath + 'clinks/js/ckeditor/plugins/clink/');

CKEDITOR.editorConfig = function( config ) {
	config.extraPlugins = 'clink';
	config.removePlugins = 'link';
};

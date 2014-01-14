<?php
/**
 * Routes
 *
 */
//	Croogo::hookRoutes('Clinks');

Configure::write('Clinks.confirmMessage',
	'By clicking OK, you are leaving the ' . Configure::read('Site.title') . ' site. Please confirm.');

/**
 * Helper
 *
 * This plugin's Example helper will be loaded via NodesController.
 */
	Croogo::hookHelper('Nodes', 'Clinks.Clinks');
//	Croogo::hookHelper('Contacts', 'Clinks.Clinks');
//	Croogo::hookHelper('ClinkAttachments', 'Ckeditor.Ckeditor');
	
/**
 * TODO maybe make this conditional on some setting/option?
 */
Configure::write('Wysiwyg.attachmentBrowseUrl', array(
	'plugin' => 'clinks',
	'controller' => 'clink_attachments',
	'action' => 'browse',
));
Configure::write('Wysiwyg.articleBrowseUrl', array(
	'plugin' => 'clinks',
	'controller' => 'clink_nodes',
	'action' => 'browse',
));


/**
 * Hook in the Ckeditor helper when browsing attachments.
 */
Croogo::mergeConfig('Wysiwyg.actions', array(
	'ClinkAttachments/admin_browse',
	'ClinkNodes/admin_browse',
	'Nodes/admin_add' => array(
		0 => array(
			'customConfig' => Configure::read('Clink.basePath') . 'clinks/js/ckeditor/config.js',
		),
	),
	'Nodes/admin_edit' => array(
		0 => array(
			'customConfig' => Configure::read('Clink.basePath') . 'clinks/js/ckeditor/config.js',
		),
	),
));

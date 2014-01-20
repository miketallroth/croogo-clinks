<?php

/**
 * Link confirmation message.
 * This is shown when following a link with this option selected.
 * TODO install this as a setting at plugin activation time.
 */
Configure::write('Clinks.confirmMessage',
	'By clicking OK, you are leaving the ' . Configure::read('Site.title') . ' site. Please confirm.');

/**
 * Helper
 *
 * The Clinks helper will be loaded into all controllers.
 */
	Croogo::hookHelper('*', 'Clinks.Clinks');
	
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
 * Force the use of a custom ckeditor config file which
 * hooks in the custom ckedior clink plugin.
 * Uses the basePath setting because at time of bootstrapping
 * the Router class doesn't know the base path yet, so
 * at Plugin enable time (in ClinksActivation), we capture
 * and store the basePath.
 */
$action_config = array(
	0 => array(
		'customConfig' => Configure::read('Clink.basePath') . 'clinks/js/ckeditor/config.js',
	),
);
Croogo::mergeConfig('Wysiwyg.actions', array(
	'ClinkAttachments/admin_browse',
	'ClinkNodes/admin_browse',
	'Nodes/admin_add' => $action_config,
	'Nodes/admin_edit' => $action_config,
));

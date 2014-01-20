<?php

App::uses('CroogoPlugin', 'Extensions.Lib');

/**
 * Clinks Activation
 *
 * Activation class for Clinks plugin.
 * This is used to ensure Ckeditor is after Clinks in Hook.bootstraps setting.
 *
 */
class ClinksActivation {

/**
 * onActivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
	public function beforeActivation(&$controller) {
		return true;
	}

/**
 * Called after activating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onActivation(&$controller) {

		// Deactivate and re-activate the Ckeditor plugin.
		// This ensures the Ckeditor bootstrap is read AFTER the Clinks bootstrap.
		$CP = new CroogoPlugin();
		$result = true;
		$editor_active = $CP->isActive('Ckeditor');

		// since plugin.js says we require ckeditor, this should always be true
		if ($editor_active === true) {
			$result = $CP->deactivate('Ckeditor');
		}
		if ($result === true) {
			$result = $CP->activate('Ckeditor');
		} else {
			CakeLog::write('error',"While activating Clinks Plugin, an error occurred deactivating Ckeditor Plugin. ({$result})");
			return;
		}
		if ($result !== true) {
			CakeLog::write('error',"While activating Clinks Plugin, an error occurred re-activating Ckeditor Plugin. ({$result})");
			return;
		}

		// Insert basePath setting for use while hooking clink plugin into ckeditor
		// during bootstrap process.
		$setting = $controller->Setting->find('first', array(
			'conditions' => array(
				'key' => 'Clinks.basePath',
			),
		));
		if (!$setting) {
			$controller->Setting->create();
			$controller->Setting->save(array(
				'key' => 'Clinks.basePath',
				'value' => Router::url('/'),
				'title' => 'App base path',
				'description' => 'For use during bootstrapping',
				'input_type' => 'text',
				'editable' => 0,
				'weight' => 31,
				'params' => '',
			));
		}
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}

	public function onDeactivation(&$controller) {
		// Remove basePath setting
		$controller->Setting->deleteAll(array(
			'key' => 'Clinks.basePath',
		));
	}

}

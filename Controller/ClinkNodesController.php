<?php

App::uses('NodesController', 'Nodes.Controller');

/**
 * Nodes Controller
 *
 * This file provides a custom node browser for Ckeditor.
 */
class ClinkNodesController extends NodesController {


/**
 * Admin browse
 *
 * @return void
 * @access public
 */
	public function admin_browse() {
		$this->layout = 'admin_popup';
		$this->admin_index();
		$this->render('Clinks.ClinkNodes/admin_browse');
	}

}

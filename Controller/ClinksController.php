<?php

App::uses('ClinksAppController', 'Clinks.Controller');

class ClinksController extends ClinksAppController {

	public $name = 'Clinks';

	public $components = array(
		'Search.Prg' => array(
			'presetForm' => array(
				'paramType' => 'querystring',
			),
			'commonProcess' => array(
				'paramType' => 'querystring',
				'filterEmpty' => true,
			),
		),
	);

	public $uses = array(
		'Nodes.Node',
	);

	public function admin_nodes_index() {
		CakeLog::write('debug',print_r($this->request,true));

		// move request data from Clink to Node
		if (array_key_exists('Clink',$this->request->data)) {
			$this->request->data['Node'] = $this->request->data['Clink'];
		}

		$this->set('title_for_layout', __d('croogo', 'Content'));
		$this->Prg->commonProcess();

		$this->Node->recursive = 0;
		$this->paginate['Node']['order'] = 'Node.created DESC';
		$this->paginate['Node']['conditions'] = array();
		$this->paginate['Node']['contain'] = array('User');

		$types = $this->Node->Taxonomy->Vocabulary->Type->find('all');
		$typeAliases = Hash::extract($types, '{n}.Type.alias');
		$this->paginate['Node']['conditions']['Node.type'] = $typeAliases;
		$nodes = $this->paginate($this->Node->parseCriteria($this->request->query));
		$nodeTypes = $this->Node->Taxonomy->Vocabulary->Type->find('list', array(
			'fields' => array('Type.alias', 'Type.title')
		));
		$this->set(compact('nodes', 'types', 'typeAliases', 'nodeTypes'));

		$this->layout = 'admin_popup';
	}


}

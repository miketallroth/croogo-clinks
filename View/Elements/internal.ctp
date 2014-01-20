<?php
	if (is_array($type) || empty($type)) {
		$type = 'page';
	}
	$slug = $slug ? $slug : 'missing-link';
	$value = $value ? $value : $slug;

	// TODO how to get other options into link call?
	// (should handle all the regular <a> options)

	echo $this->Html->link($value, array(
		'plugin' => 'nodes',
		'controller' => 'nodes',
		'action' => 'view',
		'type' => $type,
		'slug' => $slug,
	));
?>

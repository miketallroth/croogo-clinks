<?php

	// TODO determine how to pass all <a> options
	// see comments in internal.ctp element
	echo $this->element('Clinks.internal', array(
		'type' => $type,
		'slug' => $slug,
		'value' => $value,
	));
?>

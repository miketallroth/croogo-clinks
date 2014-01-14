<div class="nodes index">

	<h2><?php echo $title_for_layout; ?></h2>

	<table class="table table-striped">
	<?php
		$tableHeaders = $this->Html->tableHeaders(array(
			$this->Paginator->sort('id', __d('croogo', 'Id')),
			$this->Paginator->sort('type', __d('croogo', 'Type')),
			$this->Paginator->sort('title', __d('croogo', 'Title')),
			$this->Paginator->sort('status', __d('croogo', 'Status')),
			$this->Paginator->sort('created', __d('croogo', 'Created')),
			__d('croogo', 'Actions'),
		));
		echo $tableHeaders;

		$rows = array();
		foreach ($nodes as $node):

			$url = $this->Html->url(array(
				'controller' => 'nodes',
				'action' => 'view',
				'type' => $node['Node']['type'],
				'slug' => $node['Node']['slug'],
			));

			$insertCode = $this->Html->link('', '#', array(
				'onclick' => "Croogo.Wysiwyg.choose('" . $url . "');",
				'icon' => 'paper-clip',
				'tooltip' => __d('croogo', 'Insert')
			));


			$rows[] = array(
				$node['Node']['id'],
				__d('croogo', $nodeTypes[$node['Node']['type']]),
				$node['Node']['title'],
				$this->Layout->status($node['Node']['status']),
				$this->Time->niceShort($node['Node']['created']),
				$insertCode,
			);
		endforeach;

		echo $this->Html->tableCells($rows);
		echo $tableHeaders;
	?>
	</table>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="pagination">
		<ul>
			<?php echo $this->Paginator->first('< ' . __d('croogo', 'first')); ?>
			<?php echo $this->Paginator->prev('< ' . __d('croogo', 'prev')); ?>
			<?php echo $this->Paginator->numbers(); ?>
			<?php echo $this->Paginator->next(__d('croogo', 'next') . ' >'); ?>
			<?php echo $this->Paginator->last(__d('croogo', 'last') . ' >'); ?>
		</ul>
		</div>
		<div class="counter"><?php echo $this->Paginator->counter(array('format' => __d('croogo', 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></div>
	</div>
</div>

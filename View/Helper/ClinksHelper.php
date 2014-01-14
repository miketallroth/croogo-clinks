<?php

/**
 * Croogo Links Helper
 *
 * PHP version 5
 *
 * @category Clinks.Helper
 * @package  Clinks.View.Helper
 * @version  0.1
 * @author   Mike Tallroth
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.goclearsky.com
 */
class ClinksHelper extends AppHelper {

/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'Html',
	);

	// new experiment (???)
	public function beforeRender($viewFile) {
		// TODO overwrite this
		//Configure::write('Js.Wysiwyg.uploadsPath', Router::url('/uploads/'));

		Configure::write('Js.Wysiwyg.articlesPath',
			$this->Html->url(Configure::read('Wysiwyg.articleBrowseUrl'))
		);

		// TODO is this necessary?
		//$this->Html->script('/clinks/js/wysiwyg', array('inline' => false));
	}

	/**
	 * constructor
	 */
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view);
		$this->_setupEvents();
	}

	/**
	 * setup events
	 */
	protected function _setupEvents() {
		$events = array(
			'Helper.Layout.beforeFilter' => array(
				'callable' => 'filter', 'passParams' => true,
			),
		);
		$eventManager = $this->_View->getEventManager();
		foreach ($events as $name => $config) {
			$eventManager->attach(array($this, 'filter'), $name, $config);
		}
	}

	/**
	 * filter
	 */
	public function filter(&$content) {
		// replace all the hand written Clinks [internal:page slug=about]
		preg_match_all('/\[(internal|i):([A-Za-z0-9_\-]*)(.*?)\]/i', $content, $tagMatches);
		for ($i = 0, $ii = count($tagMatches[1]); $i < $ii; $i++) {
			$regex = '/([\w-]+)=[\'"]?((?:.(?![\'"]?\s+(?:\S+)=|[>\'"]))*.)[\'"]?/i';

			preg_match_all($regex, $tagMatches[3][$i], $attributes);
			$type = $tagMatches[2][$i];
			$options = array();
			for ($j = 0, $jj = count($attributes[0]); $j < $jj; $j++) {
				$options[$attributes[1][$j]] = $attributes[2][$j];
			}
			$content = str_replace(
				$tagMatches[0][$i], $this->makeLink($type, $options), $content);
		}

		// replace all the auto selected CLinks <a href="#" data-clink-slug="about">About</a>
		preg_match_all('@\<\s*?a((?:\b(?:\'[^\']*\'|"[^"]*"|[^\>])*)?)\>((?:(?>[^\<]*)|(?R))*)\<\/a(?:\b[^\>]*)?\>@i', $content, $tagMatches);

		for ($i = 0, $ii = count($tagMatches[1]); $i < $ii; $i++) {
			$regex = '/([\w-]+)=[\'"]?((?:.(?![\'"]?\s+(?:\S+)=|[>\'"]))*.)[\'"]?/i';

			preg_match_all($regex, $tagMatches[1][$i], $attributes);

			// keys will now contain attribute => index of attribute
			$keys = array_flip($attributes[1]);

			$confirmMessage = '';
			if (array_key_exists('data-clink-confirm',$keys)) {
				$confirmMessage = ' onclick="return confirm(\'' .
					Configure::read('Clinks.confirmMessage') .
					'\');"';
			}

			if (array_key_exists('data-clink-slug',$keys)) {
				$slug = $attributes[2][$keys['data-clink-slug']];
				$type = array_key_exists('data-clink-type',$keys) ?
					$attributes[2][$keys['data-clink-type']] : 'page';
				$url = $this->makeUrl($type, $slug);
				$replacement = $url . $confirmMessage;
				$content = str_replace(
					$attributes[0][$keys['href']], $replacement, $content);
			}

		}
		return $content;
	}

	/**
	 * Returns a proper href attribute for an HTML link tag based on
	 * an internal content item or attachment defined by a type and slug.
	 */
	public function makeUrl($type, $slug) {
		if ($type == 'attachment' || $type == 'att') {
			$url = $this->Html->url('/uploads/' . $slug);
		} else {
			$url = $this->Html->url(array(
				'controller' => 'nodes',
				'action' => 'view',
				'type' => $type,
				'slug' => $slug,
			));
		}
		$url = 'href="' . $url . '"';
		return $url;
	}

	public function makeLink($type, $options) {
		CakeLog::write('debug',print_r($options,true));

		$slug = (array_key_exists('slug', $options)) ? $options['slug'] : 'Missing Link';
		$value = (array_key_exists('value', $options)) ? $options['value'] : $slug;

		if ($type == 'attachment' || $type == 'att') {

			// handle attachments
			// TODO find attachment via model, get path from record, don't assume 'uploads'
			$options['target'] = '_blank';
			$link = $this->Html->link($value, '/uploads/' . $slug, $options);

		} else {

			// handle nodes
			$link = $this->Html->link($value, array(
				'controller' => 'nodes',
				'action' => 'view',
				'type' => $type,
				'slug' => $slug,
			), $options);
		}
		return $link;

	}
}
?>

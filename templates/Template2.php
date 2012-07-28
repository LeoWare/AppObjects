<?php

/**
 * Template2 - A Simple Templating Engine for PHP
 *
 * @author samuel
 * @package Template2
 */
class Template2 {

	var $template;

	var $title;

	var $cssLinks;
	var $scripts;
	var $tags;

	var $sections;
	var $currentSection;

	public function __construct() {

	}

	public function template($template = null) {
		// if $template is a file, load it.
		if(file_exists($template))
			$this->template = file_get_contents($template);
		else
			$this->template = $template;
	}

	public function replace($replace = array()) {
		if(!count($replace))
			return;

		$needles = array_keys($replace);
		$values = array_values($replace);

		$temp = str_replace($needles, $values, $this->template);

		$this->template = $temp;
	}

	public function display($echo = true) {
		if($echo)
			echo $this->template;

		return $this->template;
	}

	public function startSection($section) {
		$this->currentSection = $section;
		ob_start();
	}

	public function endSection($section) {
		$content = ob_get_clean();
		if(isset($this->sections[$section])) {
			$this->sections[$section] .= $content;
		} else {
			$this->sections[$section] = $content;
		}
	}

	public function getSection($section) {
		if(isset($this->sections[$section])) {
			return $this->sections[$section];
		}
		return '';
	}
	/**
	 * Creates a general tag with attributes.
	 *
	 * @param string $tag
	 * @param array $attrs
	 * @param string $content
	 * @param bool $selfClose
	 */
	public function tag($tag, $attrs = array(), $content = null, $selfClose = false) {
		$this->tags[$tag] = array($attrs, $content, $selfClose);

		$tagstr = '<'.$tag;
			if(count($attrs)>0) {
				foreach($attrs as $attr => $value) {
					$tagstr .= ' '.$attr.'="'.$value.'"';
				}
			}
			if(!is_null($content)) {
				$selfClose = false;
				$tagstr .= '>'.$content;
			}
		$this->tags[] = '<'.$tag;

	}

	/**
	 *  adds a CSS <link> tag
	 */
	public function cssLink($href = null, $media = "all", $type="text/css") {
		$this->cssLinks[$href] = array($media, $type);
	}

	// page_title

	public function title($title) {
		$this->title = str_replace(array('[TITLE]'),
						array($title),
						WEBSITE_TITLE);
	}



}

?>

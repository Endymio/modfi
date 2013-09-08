<?php

namespace Cubicle;

class GalleryService {
	private $galleries = array();

	public function __construct() {
		$this->galleries = \Spyc::YAMLLoad(ROOT_DIR . 'content/galleries.yaml');
		foreach ($this->galleries AS $id => $gallery) {
			$this->galleries[$id]['date'] = date('j.n.Y', strtotime($gallery['date']));
		}
	}

	public function getGalleries() {
		return $this->galleries;
	}
}

function addLazyLoadClass($html) {
	$content = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
	$dom = new DOMDocument();
	@$dom->loadHTML($content);

	// Convert Images
	$images = array();

	foreach ($dom->getElementsByTagName('img') as $node) {
		$images[] = $node;
	}

	foreach ($images as $node) {
		if ($node->hasAttribute("skip-lazy")) {
			continue;
		}
		if ($node->hasAttribute('src')) {
			$oldsrc = $node->getAttribute('src');
			$node->setAttribute('data-src', $oldsrc);
			$node->removeAttribute('src');
		}
		if ($node->hasAttribute('srcset')) {
			$oldsrcset = $node->getAttribute('srcset');
			$node->setAttribute('data-srcset', $oldsrcset);
			$node->removeAttribute('srcset');
		}
		$classes = $node->getAttribute('class');
		$newclasses = $classes . ' lazy';

		$newclasses = preg_replace('/( ){2,}/', ' ', $newclasses);
		$newclasses = trim($newclasses);

		$newclasses = implode(' ', array_unique(explode(' ', $newclasses)));

		$node->setAttribute('class', $newclasses);
	}
	return $dom->saveHTML();
}

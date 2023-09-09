<?php
namespace MediaWiki\Extension\HackHitchinInventory\SpecialPages;

class Locations extends \SpecialPage {
	function __construct() {
		parent::__construct( 'HackHitchinInventory-Locations' );
	}

    /**
     * Override the parent to set where the special page appears on Special:SpecialPages
     * 'other' is the default. If that's what you want, you do not need to override.
     * Specify 'media' to use the <code>specialpages-group-media</code> system interface message, which translates to 'Media reports and uploads' in English;
     * 
     * @return string
     */
    function getGroupName() {
        return 'inventory';
    }

	function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		# Get request data from, e.g.
		$param = $request->getText( 'param' );

		# Do stuff
		# ...
		$wikitext = 'Hello world!2';
		$output->addWikiTextAsInterface( $wikitext );
	}
}
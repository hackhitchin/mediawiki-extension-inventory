<?php
namespace MediaWiki\Extension\HackHitchinInventory\SpecialPages;

use MediaWiki\MediaWikiServices;
use \HTMLForm;
use Wikimedia\Rdbms\LBFactory;
use MediaWiki\Extension\HackHitchinInventory\Models\Location;
use MediaWiki\Extension\HackHitchinInventory\Services\LocationService;

class Special extends \SpecialPage {
	/** @var LBFactory */
	private $lbFactory;
	/** @var LocationService */
	private $locationService;

	/**
	 * @param LBFactory $lbFactory
	 */
	function __construct(LocationService $locationService) {
		$this->locationService = $locationService;
		parent::__construct( 'HackHitchinInventory' );
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

	private function form() {
		// formDescriptor Array to tell HTMLForm what to build
		$formDescriptor = [
			'key' => [
				'label' => 'Key', // Label of the field
				'class' => 'HTMLTextField', // Input type
				// Call validateSimpleTextField() within your extends SpecialPage class
				'validation-callback' => [ Location::class, 'validateKey' ],
			],
			'description' => [
				'label' => 'Description', // Label of the field
				'class' => 'HTMLTextField', // Input type
			],
		];

		// Build the HTMLForm object
		$htmlForm = HTMLForm::factory( 'table', $formDescriptor, $this->getContext() );

		// Text to display in submit button
		$htmlForm->setSubmitText( 'Create Location' );

		// We set a callback function
		$htmlForm->setSubmitCallback( [ $this, 'processInput' ] );  
		// Call processInput() in your extends SpecialPage class on submit

		return $htmlForm;
	}

	function execute( $par ) {
		$locations = $this->locationService->getAll();
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		# Get request data from, e.g.
		$param = $request->getText( 'param' );

		# Do stuff
		# ...
		$wikitext = 'Hello world!';
		foreach ($locations as $location) {
			$wikitext .= " {$location->getId()} ";
			$wikitext .= " {$location->getKey()} ";
			$wikitext .= " {$location->getDescription()} ";
		}
		$wikitext .= "\n {{#example: hello | hi | hey}}";
		$output->addWikiTextAsInterface( $wikitext );

		$this->form()->show();
	}

	// Callback function
	// OnSubmit Callback, here we do all the logic we want to do…
	public function processInput( $formData ) {
		$location = Location::create($formData['key'], $formData['description']);

		$this->locationService->store($location);

		return true;
	}
}
<?php
namespace MediaWiki\Extension\HackHitchinInventory\Parsers;

use MediaWiki\Hook\ParserFirstCallInitHook;

class ParserHooks implements ParserFirstCallInitHook {

	private LocationParser $locationParser;

	public function __construct(LocationParser $locationParser) {
		$this->locationParser = $locationParser;
	}

	/**
	 * This hook is called when the parser initialises for the first time.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Parser object being initialised
	 * @return bool|void True or no return value to continue or false to abort
	 */
   public function onParserFirstCallInit( $parser ) {

      // Create a function hook associating the "example" magic word with renderExample()
      $parser->setFunctionHook( 'example', [ $this->locationParser, 'renderExample' ] );
   }
}
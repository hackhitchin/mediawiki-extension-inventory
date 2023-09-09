<?php
namespace MediaWiki\Extension\HackHitchinInventory\Parsers;

use Parser;
use MediaWiki\Extension\HackHitchinInventory\Services\LocationService;

class LocationParser {
   private LocationService $locationService;

   public function __construct(LocationService $locationService) {
      $this->locationService = $locationService;
   }

   // Render the output of {{#example:}}.
   // The input parameters are wikitext with templates expanded.
   // The output should be wikitext too.
   public function renderExample( Parser $parser) {
      $locations = $this->locationService->getAll();

      $output = <<<END
{| class="wikitable" style="margin:auto"
|+ Locations
|-
! Key !! Description
|-
END;

      foreach($locations as $location) {
         $output .= "| {$location->getKey()} || {$location->getDescription()}\n";
         $output .= "|-\n";
      }

// | Example || Example
// |-
// | Example || Example
// |-
// | Example || Example
      $output .= "|}\n";

      return $output;
   }
}
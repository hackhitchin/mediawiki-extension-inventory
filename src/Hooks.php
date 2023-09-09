<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
 */

namespace MediaWiki\Extension\HackHitchinInventory;

use DatabaseUpdater;
use Parser;
use MediaWiki\MediaWikiServices;
use MediaWiki\Hook\BeforePageDisplayHook;
use MediaWiki\Hook\MediaWikiServicesHook;
use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook ;
use MediaWiki\Extension\HackHitchinInventory\Parsers\LocationParser;
use MediaWiki\Extension\HackHitchinInventory\Services\LocationService;

class Hooks implements BeforePageDisplayHook, MediaWikiServicesHook, LoadExtensionSchemaUpdatesHook {

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 * @param \OutputPage $out
	 * @param \Skin $skin
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		$config = $out->getConfig();
		if ( $config->get( 'HackHitchinInventoryVandalizeEachPage' ) ) {
			$out->addModules( 'oojs-ui-core' );
			$out->addHTML( \Html::element( 'p', [], 'HackHitchinInventory was here' ) );
		}
	}

	/**
	 * This hook is called when a global MediaWikiServices instance is initialized.
	 * Extensions may use this to define, replace, or wrap services. However, the
	 * preferred way to define a new service is the $wgServiceWiringFiles array.
	 *
	 * @warning Implementations must not immediately access services instances from the
	 * service container $services, since the service container is not fully initialized
	 * at the time when the hook is called. However, callbacks that are used as service
	 * instantiators or service manipulators may access service instances.
	 * 
	 * @param MediaWikiServices $services
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiServices( $services ) {
	    // The service wiring and configuration in $services may be incomplete at this time,
	    // do not access services yet!
	    // At this point, we can only manipulate the wiring, not use it!
	
	    $services->defineService(
	       'LocationService',
	        function( MediaWikiServices $container ) {
	            // It's ok to access services inside this callback, since the
	            // service container will be fully initialized when it is called!
	            return new LocationService( $container->getDBLoadBalancerFactory() );
	        }
	    );

		$services->defineService(
			'LocationParser',
			function( MediaWikiServices $container ) {
				return new LocationParser( $container->get('LocationService' ));
			}
		);
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/LoadExtensionSchemaUpdates
	 * @see https://m.mediawiki.org/wiki/Manual:SQL_patch_file
	 * @param DatabaseUpdater $out
	 */
	public function onLoadExtensionSchemaUpdates( $updater ) {
		$dbType = $updater->getDB()->getType();
		$dir = __DIR__ . "/../sql";
		$path = $dbType === 'mysql' ? "$dir/tables-generated.sql" : "$dir/$dbType/tables-generated.sql";

		$updater->addExtensionTable(
			'hack_hitchin_inventory',
			$path
		);
	}
}

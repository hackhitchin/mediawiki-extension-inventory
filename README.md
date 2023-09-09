# Hichin Hackspace Inventory Plugin
[![Status](https://img.shields.io/badge/status-wip-orange)](https://github.com/facebook/react/blob/main/LICENSE)
[![License: GPL v2](https://img.shields.io/badge/License-GPL_v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![Build](https://img.shields.io/badge/build-missing-red)](https://github.com/facebook/react/blob/main/LICENSE)

A MediaWiki plugin developed by Hitchin Hackspace to track the location of our
tools and consumables.

# Development

First set up a local copy of MediaWiki by following the [Developer Guide][1].
You will also need node + npm.

Install a skin following the [Skin configuration][2] guide.

Clone this repo and install dependencies:

```bash
git clone git@github.com:hackhitchin/mediawiki-extension-inventory.git extentions/HackHitchinInventory
cd extensions/HackHitchinInventory
npm install
docker-compose exec -w /var/www/html/w/extensions/HackHitchinInventory mediawiki composer install
```

Add the following to the bottom of `LocalSettings.php`:

```php
wfLoadExtension( "HackHitchinInventory");
$wgHackHitchinInventoryVandalizeEachPage = true;
```

Run the database patches:

```bash
docker-compose exec -w /var/www/html/w/extensions/HackHitchinInventory mediawiki composer update-db
```

Running tests:

```bash
docker-compose exec -w /var/www/html/w/extensions/HackHitchinInventory mediawiki composer test
npm test
```

## Other options

 * Run composer locally instead of inside docker.
 * Run mediawiki on a local server instead of docker.

# Database

Database schemas use the new abstract schema changes available from Mediawiki.
The JSON files are stored in ./abstractSchemaChanges/ and the generated SQL is
stored in ./sql/ the default is for MySQL, other databases are stored in named
folders.

To generate SQL:

```bash
docker-compose exec -w /var/www/html/w/extensions/HackHitchinInventory mediawiki composer generate-sql
```

To apply changes:

```bash
docker-compose exec -w /var/www/html/w/extensions/HackHitchinInventory mediawiki composer update-db
```

# To document

 * Code standards (see https://www.mediawiki.org/wiki/Continuous_integration/Entry_points).
 * Code layout
 * Features

[1]: https://gerrit.wikimedia.org/g/mediawiki/core/+/HEAD/DEVELOPERS.md "MediaWiki Developer Guide"
[2]: https://www.mediawiki.org/wiki/Manual:Skin_configuration "MediaWiki Skin Configuration"
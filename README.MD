# Palamon [![PHP Composer](https://github.com/unisys12/palamon/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/unisys12/palamon/actions/workflows/php.yml) ![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/unisys12/palamon)

This project is currently being rewritten so that I can hone my PHP chops, once again and get this package working with the latest version of the Bungie API as well as maintain backwards compatibility with the older API as well.

## Package Goals

The goal of this package is to create a very simple to use interface with Bungie's Public Manifest to the game Destiny. It should not be tied directly to or have a dependency on any framework _(Laravel/Symfony)_.

## Package Contents

Currently, it only contains methods to retrieve the Destiny manifest, download the Mobile World Contents database and the entire Grimoire. As mentioned above, it is currently being rewritten.

## Usage

To use this package, simple download it _(currently not on Packagist)_ from GitHub or fetch it into your project using a method similar to the [following](https://getcomposer.org/doc/02-libraries.md#publishing-to-a-vcs). Run `composer update` to install dependencies. Next, create a `.env` file in the root of the project and place your `X-API-KEY` within it, like so...

```shell
#
# Bungie Destiny Environment Variables
#

BUNGIE_KEY="Your Personal Bungie X-API-KEY Goes Here!"
```

# Destiny 1

From here, you should be good to go. Bear in mind, I am still working on this, so... take it with a grain of salt. Also, all the below examples are for Destiny 1. Destiny 2 examples will be added when I have something better working.

## Grimoire Example

```php

<?php
// Load Composer Dependenices
require __DIR__ . '/vendor/autoload.php';

use Palamon\Destiny\Requests\Grimoire;

// Load DotEnv to handle Enviroment Variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$grimoire = new Grimoire();
$cards = $grimoire->getGrimoire();

$themes = $cards['Response']['themeCollection'];

```

Now, you have an array containing all the current Grimoire.

## Manifest Example

Let's say, you want to download the compressed SQLite DB that contains all the static data related to Destiny.

```php
<?php

// Load Composer Dependenices
require __DIR__ . '/vendor/autoload.php';

use Palamon\Destiny\Requests\Manifest;

// Load DotEnv to handle Enviroment Variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$request = new Manifest();

$request->downloadMobileWorldContents();
```

Within the root of your project, a new `/cache` directory will be created, if one doesn't already exsit. Within this directory will be a pretty large `content` file that you can open with a SQLite Viewer and browse the full contents of the database. Please note that the database will contain an `id` column along with a `Blob` column which contains JSON, not what you are probably expecting. Think of it as, a local API result.

## Items Example

So, let's say you want to display an single item from the Armory or from a Vendor. To do that, you can use the `getSingleItem` method from the Item Class, like so...

```php
<?php

// Load Composer Dependenices
require __DIR__ . '/vendor/autoload.php';

use palamon\Destiny\Requests\Items;

// Load DotEnv to handle Enviroment Variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$request = new Items();

$key = '4028115470';

$item = $request->getSingleItem($key);
?>
```

```php
use palamon\Destiny\Requests\Items;

$request = new Items();

$items = $request->getAllItems('definitions=true');

var_dump($items);
```

This will give you a really good idea of what is included, which is a lot so...

## Talent Node Example

To access a list of all the Talent Nodes, you could do the following:

```php
use palamon\Destiny\Requests\TalentNodes;

$request = new TalentNodes();

$talents = $request->getTalentNodes();

echo '<h3>Talent Nodes</h3>';
var_dump($talents);

echo '<ul>';
foreach ($talents['Response']['data']['stepHashes'] as $talent) {
  echo '<li>' . $talent . '</li>';
}
echo '</ul>';

echo '<p><b>' .
  count($talents['Response']['data']['stepHashes']) .
  '</b> out of <b>' .
  $talents['Response']['data']['totalResults'] .
  '</b></p>';
```

But as you can see, this will only return a list of hashes. Not of great use. As with most of these, there are several Url Parameters we can pass to the method which will give us a bit more information. Like, so:

```php
use Palamon\Destiny\Requests\TalentNodes;

$request = new TalentNodes();

$talents = $request->getTalentNodes('definitions=true');

echo '<h3>Talent Nodes</h3>';
var_dump($talents);

echo '<dl>';
foreach ($talents['Response']['data']['stepHashes'] as $talent) {
  $talentHash = $talent;
}

foreach ($talents['Response']['definitions']['nodeSteps'] as $def) {
  echo '<dt>' .
    $talentHash .
    ' - ' .
    '<strong>' .
    $def['nodeStepName'] .
    '</strong>';
  echo '<dd>' . $def['nodeStepDescription'];
}

echo '<p><b>' .
  count($talents['Response']['data']['stepHashes']) .
  '</b> out of <b>' .
  $talents['Response']['data']['totalResults'] .
  '</b></p>';
```

# Destiny 2

## Response Object

All objects returned in a given response have a property layout that can be viewed [here](https://bungie-net.github.io/multi/operation_get_Destiny2-GetDestinyManifest.html#operation_get_Destiny2-GetDestinyManifest) in the official Bungie Docs. Everything returned from this package originates from the `Response` property.

## Manifest

To obtain the latest copy of the Manifest, simple call the Destiny 2 Manifest class and pass the language that you would like to have returned as the only parameter:

```php
$manifest = new Manifest('en');
```

There are currently 12 languages supported in Destiny 2 and they are as follows:

```
"en": "English",
"fr": "French",
"es": "Spanish",
"es-mx": "Spanish - Mexico",
"de": "German",
"it": "Italian",
"ja": "Japanese",
"pt-br": "Portuguese",
"ru": "Russian",
"pl": "Polish",
"ko": "Korean",
"zh-cht": "Chinese (Traditional)",
"zh-chs": "Chinese (Simplified)"
```

## JSONWorldComponents

There is two methods that allow access to the Destiny 2 JSON data directly, instead of the SQLite database. If this is what your after, then:

### `getAllJson()`

`getAllJson()` returns the whole dataset in JSON. _Be Aware that this is big dataset and use sparingly, if at all!_. IIRC, the download size of this is roughly 30 Mbs. Don't say I didn't warn ya!

```php
use Palamon\Destiny2\Requests\Manifest;

$manifest = new Manifest('en');
$wholeLotaJSON = $manifest->getAllJson();
```

### `getJsonTable(string $tableName)`

`getJsonTable()` returns a single table of data as JSON. Be Aware that there is a lot tables to choose from. Bungie also states in their docs that this data is not to reliable upon.

```php
$manifest = new Manifest('en');
$jsonTable = $manifest->getJsonTable('DestinyLoreDefinition');
```

List of Currently available tables:

```
DestinyNodeStepSummaryDefinition
DestinyArtDyeChannelDefinition
DestinyArtDyeReferenceDefinition
DestinyPlaceDefinition
DestinyActivityDefinition
DestinyActivityTypeDefinition
DestinyClassDefinition
DestinyGenderDefinition
DestinyInventoryBucketDefinition
DestinyRaceDefinition
DestinyTalentGridDefinition
DestinyUnlockDefinition
DestinyMaterialRequirementSetDefinition
DestinySandboxPerkDefinition
DestinyStatGroupDefinition
DestinyProgressionMappingDefinition
DestinyFactionDefinition
DestinyVendorGroupDefinition
DestinyRewardSourceDefinition
DestinyUnlockValueDefinition
DestinyRewardMappingDefinition
DestinyRewardSheetDefinition
DestinyItemCategoryDefinition
DestinyDamageTypeDefinition
DestinyActivityModeDefinition
DestinyMedalTierDefinition
DestinyAchievementDefinition
DestinyActivityGraphDefinition
DestinyActivityInteractableDefinition
DestinyBondDefinition
DestinyCharacterCustomizationCategoryDefinition
DestinyCharacterCustomizationOptionDefinition
DestinyCollectibleDefinition
DestinyDestinationDefinition
DestinyEntitlementOfferDefinition
DestinyEquipmentSlotDefinition
DestinyStatDefinition
DestinyInventoryItemDefinition
DestinyInventoryItemLiteDefinition
DestinyItemTierTypeDefinition
DestinyLocationDefinition
DestinyLoreDefinition
DestinyMetricDefinition
DestinyObjectiveDefinition
DestinyPlatformBucketMappingDefinition
DestinyPlugSetDefinition
DestinyPowerCapDefinition
DestinyPresentationNodeDefinition
DestinyProgressionDefinition
DestinyProgressionLevelRequirementDefinition
DestinyRecordDefinition
DestinyRewardAdjusterPointerDefinition
DestinyRewardAdjusterProgressionMapDefinition
DestinyRewardItemListDefinition
DestinySackRewardItemListDefinition
DestinySandboxPatternDefinition
DestinySeasonDefinition
DestinySeasonPassDefinition
DestinySocketCategoryDefinition
DestinySocketTypeDefinition
DestinyTraitDefinition
DestinyTraitCategoryDefinition
DestinyUnlockCountMappingDefinition
DestinyUnlockEventDefinition
DestinyUnlockExpressionMappingDefinition
DestinyVendorDefinition
DestinyMilestoneDefinition
DestinyActivityModifierDefinition
DestinyReportReasonCategoryDefinition
DestinyArtifactDefinition
DestinyBreakerTypeDefinition
DestinyChecklistDefinition
DestinyEnergyTypeDefinition
```

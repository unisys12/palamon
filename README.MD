# Palamon [![PHP Composer](https://github.com/unisys12/palamon/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/unisys12/palamon/actions/workflows/php.yml) ![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/unisys12/palamon)

This project is currently being rewritten so that I can hone my PHP chops, once again and get this package working with the latest version of the Bungie API as well as maintain backwards compatibility with the older API as well.

### Package Goals

The goal of this package is to create a very simple to use interface with Bungie's Public Manifest to the game Destiny. It should not be tied directly to or have a dependency on any framework _(Laravel/Symfony)_.

### Package Contents

Currently, it only contains methods to retrieve the Destiny manifest, download the Mobile World Contents database and the entire Grimoire. As mentioned above, it is currently being rewritten.

### Usage

To use this package, simple download it _(currently not on Packagist)_ from GitHub or fetch it into your project using a method similar to the [following](https://getcomposer.org/doc/02-libraries.md#publishing-to-a-vcs). Run `composer update` to install dependencies. Next, create a `.env` file in the root of the project and place your `X-API-KEY` within it, like so...

```shell
#
# Bungie Destiny Environment Variables
#

BUNGIE_KEY="Your Personal Bungie X-API-KEY Goes Here!"
```

From here, you should be good to go. Bear in mind, I am still working on this, so... take it with a grain of salt. Also, all the below examples are for Destiny 1. Destiny 2 examples will be added when I have something better working.

#### Grimoire Example

```php

<?php
// Load Composer Dependenices
require __DIR__ . '/vendor/autoload.php';

use palamon\Destiny\Requests\Grimoire;

// Load DotEnv to handle Enviroment Variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$grimoire = new Grimoire();
$cards = $grimoire->getGrimoire();

$themes = $cards['Response']['themeCollection'];

```

Now, you have an array containing all the current Grimoire.

#### Manifest Example

Let's say, you want to download the compressed SQLite DB that contains all the static data related to Destiny.

```php
<?php

// Load Composer Dependenices
require __DIR__ . '/vendor/autoload.php';

use palamon\Destiny\Requests\Manifest;

// Load DotEnv to handle Enviroment Variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$request = new Manifest();

$request->downloadMobileWorldContents();
```

Within the root of your project, a new `/cache` directory will be created, if one doesn't already exsit. Within this directory will be a pretty large `content` file that you can open with a SQLite Viewer and browse the full contents of the database. Please note that the database will contain an `id` column along with a `Blob` column which contains JSON, not what you are probably expecting. Think of it as, a local API result.

#### Items Example

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

#### Talent Node Example

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
use palamon\Destiny\Requests\TalentNodes;

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

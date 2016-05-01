Palamon
=======

Palamon is meant to be a very simple PHP project, which would allow me to interact with the public side of Bungie's API for the game Destiny.  

###Package Goals
The goal of this package is to create a very simple to use interface with Bungie's Public API to the game Destiny. Although the game and API has been avaliable again for well over year, Bungie has made it pretty clear that they do not intend on writing any form of documentation on the API. Until recently when they gave us a clean outline of all public and private end-points. I hope to make this package as easy to use as possbile, which will allow anyone to add Destiny related features to their sites. This will come to pass as I add more to this package. Also, I will be using this project to get familiar with testing and that will be added in the very near future. I promise. 

###Package Contents
Currently, it only contains methods to retrieve the Destiny manifest, download the Mobile World Contents database script and the entire Grimoire. As mentioned above, there is more to come. 

###Useage
To use this package, simple download it _(currently not on Packagist)_ from github or fetch it into your project. Run `composer update` to install dependencies. Next, create a `.env` file in the root of the project and place your enviroment variables within it, like so...

```shell
# 
# Bungie Destiny Environment Variables
#

BUNGIE_KEY="Your Personal Bungie X-API-KEY Goes Here!"
BUNGIE_BASE_URL=http://www.bungie.net/Platform/Destiny
BUNGIE_GRIMOIRE_URL=${BUNGIE_BASE_URL}/Vanguard/Grimoire/Definition/
BUNGIE_MANIFEST_URL=${BUNGIE_BASE_URL}/Manifest/
BUNGIE_ITEMS_URL=${BUNGIE_BASE_URL}/Explorer/Items/
BUNGIE_TALENTNODES_URL=${BUNGIE_BASE_URL}/Explorer/TalentNodeSteps/
BUNGIE_STATS_URL=${BUNGIE_BASE_URL}/Stats/Definition/
``` 

I hope to auto-generate this for you, from which you would only have to enter your X-API-KEY, but... that's for another day. From here, you should be good to go. Bear in mind, I am still working on this, so... take it with a grain of salt.

####Grimoire Example
```php

<?php

// Load Composer Dependenices
require __DIR__ . '/vendor/autoload.php';

use \palamon\Requests\Grimoire;

// Load DotEnv to handle Enviroment Variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$grimoire = new Grimoire;
$cards = $grimoire->getGrimoire();

$themes = $cards['Response']['themeCollection'];

```

Now, you have an array containing all the current Grimoire. From here, you can iterate over it like so...

```html

<!DOCTYPE html>
<html>
<head>
	<title>Destiny Grimoire</title>
	<style>
		.cards {
		width: 100%;
		display: flex;
		flex-flow: row;
		}

		.card {
			width: 323px;
			height: 419px;
			background-image: url("http://www.bungie.net/common/destiny_content/grimoire/images/themes-sprites_113f32d6872123b0c1d988ac607e7f85.jpg");
			flex: auto;
		}

		.card header {
			color: white;
			margin-left: 30px;
			font-family: arial;
		}

	<?php 
	// Probably not the best way to go about this, but...
	foreach($themes as $card){
		.card, #{{ $card['themeId'] }}  {		
			background-position: {{ $card['normalResolution']['image']['rect']['x'] }}px {{ $card['normalResolution']['image']['rect']['y'] }}px;
		}
	}
	?>
	</style>
</head>
<body>
<div class="cards">
	<?php 
	foreach($themes as $card){
	
	<div class="card" id="{{ $card['themeId'] }}">
		<header>
			<h3>{{ $card['themeName'] }}</h3>
		</header>
	</div>

	}
	<?php
</div>
</body>
</html>

```

####Manifest Example
Let's say, you want to download everything currently in the database... ok... umm... yeah... Well, to do that, just - 

```php
<?php

// Load Composer Dependenices
require __DIR__ . '/vendor/autoload.php';

use \palamon\Requests\Manifest;

// Load DotEnv to handle Enviroment Variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$request = new Manifest();

$request->downloadMobileWorldContents();
```
Within the root of your project, a new `/cache` directory will be created, if one doesn't already exsit. Within this directory will be a pretty large `content` file that you can open with a SQLite Viewer and browse the full contents of the database. Please note that the database will contain an `id` column along with a `Blob` column which contains JSON, not what you are probably expecting. Think of it as, a local API result. 

####Items Example
So, let's say you want to display an single item from the Armory or from a Vendor. To do that, you can use the `getSingleItem` method from the Item Class, like so... 

```php
<?php

// Load Composer Dependenices
require __DIR__ . '/vendor/autoload.php';

use \palamon\Requests\Items;

// Load DotEnv to handle Enviroment Variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$request = new Items();

$key = '4028115470';

$item = $request->getSingleItem($key);
?>
```
```html
<html>
	<head>
		<title>Palamon Item Example</title>
		<style>
		.item-example {
		
		}
		.item-example>h1 {
			margin-bottom: 0;
		}

		.item-description>h5 {
			margin-top: 0;
			padding: 0;
		}
		</style>
	</head>
	<body>
		<div class="item-example">
			<?php 
			
				echo "<h1>" . $item['Response']['data']['inventoryItem']['itemName'] . "</h1>";
				echo "<div class='item-description'><h5>" . $item['Response']['data']['inventoryItem']['itemDescription'] . "</h5></div>";
				echo "<img src=http://www.bungie.net" . $item['Response']['data']['inventoryItem']['icon'] . "/>";
			?>
			<div>Very poor example... I will admit that... </div>
		</div>
	</body>
</html>
```

So, I know what your asking? *"Where the hell did you get the key from?"*. I gotcha! Well, to retrieve all the keys listed currently within the database, simple use the `getAllItems()` method, like so...

```php
$request = new Items();

$items = $request->getAllItems();

while ($items['Response']['data']['hasMore'] === TRUE) {
	foreach ($items['Response']['data']['itemHashes'] as $item) {
		echo "<ul>";
		echo "<li>" . $item . "</li>";
		echo "</ul>";
	}
}
```

All this will really do is display all the item hashes, which really doesn't do us a whole lot of good. What can do though is pass a parameter through to the method called 'definitions' and set it to TRUE. 

```php
$request = new Items();

$items = $request->getAllItems('definitions=true');

var_dump($items);
```

This will give you a really good idea of what is included, which is a lot so... 

####Talent Node Example
To access a list of all the Talent Nodes, you could do the following:

```php
use \palamon\Requests\TalentNodes;

$request = new TalentNodes();

$talents = $request->getTalentNodes();

echo "<h3>Talent Nodes</h3>";
var_dump($talents);

echo "<ul>";
foreach ($talents['Response']['data']['stepHashes'] as $talent) {
	echo "<li>" . $talent . "</li>";
};
echo "</ul>";

echo "<p><b>" . count($talents['Response']['data']['stepHashes']) . "</b> out of <b>" . $talents['Response']['data']['totalResults'] . "</b></p>";
```

But as you can see, this will only return a list of hashes. Not of great use. As with most of these, there are several Url Parameters we can pass to the method which will give us a bit more information. Like, so:


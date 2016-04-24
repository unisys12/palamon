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
</div>
</body>
</html>

```

####Manifest Example
Let's say, you want to everything currently in the database... ok... umm... yeah... Well, to do that, just - 

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
Within the root of your project, a new `/cache` directory will be created, if one doesn't already exit. Within this directory will be a pretty large `content` file that you can open with a SQLite Viewer and browse the full contents of the database. Please note that the database will contain JSON, not what you are probably expecting. Think of it as, a local API result. 
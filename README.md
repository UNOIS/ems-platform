# EMS Platform Rest Client

A fork of the unois rest client to the EMS Platform

# Requirements

- PHP >= 8.0
- [php guzzle/guzzle/](https://github.com/guzzle/guzzle/)

# Installation

1. Download and Install PHP Composer.

   ``` sh
   curl -sS https://getcomposer.org/installer | php
   ```

2. Add the following to your composer.json file.
   ```json
	"repositories": [
        {
        	"type" : "vcs",
        	"url": "https://github.com/generalludd/ems-platform"
        }
   ```
   ```json
   "require" : {
        "unoadis/ems-platform" : "dev-master"
   }
   ```

3. Then run Composer's install or update commands to complete installation. 

   ```sh
   php composer.phar install
   ```

# Example

   ```php
   require '../vendor/autoload.php';

	use EmsPlatform\Client as EmsClient;
	use EmsPlatform\EmsException;
	
	$baseUri  = 'https://baseuri.com/EmsPlatform/api/v1/;
	$clientId = '';
	$secret   = '';
	
	try {
	    echo "<pre>";
	    $ems = new EmsClient($baseUri, $clientId, $secret);
	    $ems->setDefaultPageSize(2000);
	    $buildings = $ems->getBuildings();
	    print_r($buildings);
	    echo "</pre>";
	} catch (EmsException $e) {
	    echo($e->getMessage());
	}
   ```

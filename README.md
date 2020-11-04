# ActionML PHP SDK

[![Build
Status](https://travis-ci.org/apache/predictionio-sdk-php.svg?branch=develop)](https://travis-ci.org/apache/predictionio-sdk-php)

## Prerequisites

* PHP 5.6+ (http://php.net/)
* PHP: cURL (http://php.net/manual/en/book.curl.php)
* Phing (http://www.phing.info/)
* ApiGen (http://apigen.org/)

Note: This SDK only supports ActionML version 0.6 or higher.

## Getting Started

The easiest way to install ActionML PHP client is to use
[Composer](http://getcomposer.org/).

1. `actionml` is available on [Packagist](https://packagist.org) and can be
installed using [Composer](https://getcomposer.org/):

        composer require actionml/actionml

2. Include Composer's autoloader in your PHP code

        require_once("vendor/autoload.php");

## Usage

This package is a web service client based on Guzzle.
A few quick examples are shown below.

### Instantiate ActionML API Event Client

```PHP
use actionml\EventClient;
$engineId = 'test_ur';
$client = new EventClient($engineId, 'http://localhost:9090');
```

### Set a User Record from Your App

```PHP
// assume you have a user with user ID 5
$response = $client->setUser(5);
```


### Set an Item Record from Your App

```PHP
// assume you have a book with ID 'bookId1' and we assign 1 as the type ID for book
$response = $client->setItem('bookId1', array('itypes' => 1));
```


### Import a User Action (View) form Your App

```PHP
// assume this user has viewed this book item
$client->recordUserActionOnItem('view', 5, 'bookId1');
```


### Retrieving Prediction Result

```PHP
// assume you have created an itemrank engine on localhost:9090
// we try to get ranking of 5 items (item IDs: 1, 2, 3, 4, 5) for a user (user ID 7)

$engineClient = new EngineClient('test_ur');
$response = $engineClient->queryItemSet(aray(7,4,6));

print_r($response);
```

## Bugs and Feature Requests

Use [ActionML PHP SDK Issues](https://github.com/actionml/harness/issues) to report bugs or
request new features.

## Contributing

Read the [Contribute
Code](http://predictionio.apache.org/community/contribute-code/) page.

## License

Apache PredictionIO is under [Apache 2
license](http://www.apache.org/licenses/LICENSE-2.0.html).

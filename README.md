# Unit Test Helper for API-Blueprint

## Installation

* Install [Drafter](https://github.com/apiaryio/drafter).
* Add package to composer:
```bash
$ composer require goez/apib-unit --dev
```

## Usage

```php
// Parse API-Blueprint document, and get the endpoints.
$endpoints = (new Apib('./example.apib'))->getEndpoints();

// Get first endpoints
$endpoint = $endpoints[0];

// Get first example of the endpoint
$example = $endpoint->getExamples()[0];

// Get request of example
$request = $example->getRequests()[0];

// Get response of example
$response = $example->getResponse()[0];

// Get JSON Schema of response
$schema = $response->getSchema();
```

## License

MIT

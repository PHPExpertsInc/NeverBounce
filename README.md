# NeverBounce Client

[![TravisCI](https://travis-ci.org/phpexpertsinc/NeverBounce.svg?branch=master)](https://travis-ci.org/phpexpertsinc/NeverBounce)
[![Maintainability](https://api.codeclimate.com/v1/badges/aa7f52a1d1afbf383904/maintainability)](https://codeclimate.com/github/phpexpertsinc/NeverBounce/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/aa7f52a1d1afbf383904/test_coverage)](https://codeclimate.com/github/phpexpertsinc/NeverBounce/test_coverage)

NeverBounce Client is a PHP Experts, Inc., Project aimed at easily accessing the NeverBounce API.

## Installation

Via Composer

```bash
composer require phpexperts/neverbounce
```

## Usage

```php
    // Build the client.
    $client = NeverBounceClient::build();
    
    // Quickly determine if an email is valid or not.
    $response = $client->isValid('theodore@phpexperts.pro');
    // Output: true or false
    
    // Get details as to why an email is valid or not.
    $response = $client->validate('doesnt-exist@gmail.com');

    /* Output: 
    {
      +"status": "success"
      +"result": "invalid"
      +"flags": array:4 [
        0 => "free_email_host"
        1 => "has_dns"
        2 => "has_dns_mx"
        3 => "smtp_connectable"
      ]
      +"suggested_correction": ""
      +"execution_time": 309
    }
    */
```

## Use cases

PHPExperts\NeverBounceClient  
 ✔ Can build itself  
 ✔ Will validate a good email  
 ✔ Will validate a catch all email  
 ✔ Will validate an invalid domain email  
 ✔ Will validate an invalid account email  
 ✔ Will detect free email hosts  
 ✔ Can determine if an email is good  
 ✔ Can determine if an email has an invalid domain  
 ✔ Can determine if an email has an invalid account  

## Testing

```bash
phpunit --testdox
```

## Contributors

[Theodore R. Smith](https://www.phpexperts.pro/]) <theodore@phpexperts.pro>  
GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690  
CEO: PHP Experts, Inc.

## License

MIT license. Please see the [license file](LICENSE) for more information.

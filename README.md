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

### Validate a single email address
```php
    // Build the client.
    $client = NeverBounceClient::build();
    
    // Quickly determine if an email is valid or not.
    $response = $client->isValid('theodore@phpexperts.pro');
    // Output: true or false
    
    // Get details as to why an email is valid or not.
    $emailValidationDTO = $client->validate('doesnt-exist@gmail.com');

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

### Bulk email address validation
```php
    // Build the client.
    $client = NeverBounceClient::build();

    // Create the job over at NeverBounce.
    $jobId = $client->bulkVerify(['support@neverbounce.com', 'sales@phpexperts.pro']);
    
    // Periodicly check the job for results.
    for ($a = 0; $a < 30; ++$a) {
        $bulkValidationDTO = $client->checkJob($jobId);
        if (!$bulkValidationDTO) {
            sleep(1);
        }
        
        break;
    }
    
    /** Output:
    BulkValidationDTO [
        'status'           => 'success',
        'id'               => 2917483,
        'job_status'       => 'complete',
        'filename'         => 'bulk-1559703280.csv',
        'created_at'       => Carbon: '2019-06-04 22:54:41',
        'started_at'       => Carbon: '2019-06-04 22:54:42',
        'finished_at'      => Carbon: '2019-06-04 22:54:47',
        'total'            => ListStatsDTO [
            'records'    => 7,
            'billable'   => 5,
            'processed'  => 7,
            'valid'      => 3,
            'invalid'    => 3,
            'catchall'   => 1,
            'disposable' => 0,
            'unknown'    => 0,
            'duplicates' => 1,
            'bad_syntax' => 1,
        ],
        'bounce_estimate'  => 28.571428571429,
        'percent_complete' => 100,
        'execution_time'   => 12,
    ]
     */
```

All DTOs are easily converted to an array, JSON, and are serializable.
See the [**SimpleDTO Project**](http://github.com/phpexpertsinc/SimpleDTO) for details.

* To an array: `$listStats->toArray()`
* To JSON: json_encode($listStats);
* Serialize (as JSON): serialize($listStats);

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

PHPExperts\NeverBounceClient: Bulk Validations  
 ✔ Can submit a bulk validation request  
 ✔ Can poll job until completed  
 ✔ Will retrieve bulk validation results  

## Testing

```bash
# Run without needing a NeverBounce key / not using up your free quota.
phpunit --testdox --exclude-group=thorough

# Run the full suite.
phpunit --testdox 
```

## Contributors

[Theodore R. Smith](https://www.phpexperts.pro/]) <theodore@phpexperts.pro>  
GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690  
CEO: PHP Experts, Inc.

## License

MIT license. Please see the [license file](LICENSE) for more information.

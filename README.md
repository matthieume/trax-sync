TRAX Sync
=========

## Installation

- `composer require trax/sync`
- `php artisan vendor:publish --tag=trax-sync-config`
- `php artisan migrate`


## Configuration

You can configure one or several connections in the **config/trax-sync.php`** file. 

### LRS example

```
'trax' => [

    'id' => 0,              // Define it, and don't change it. It is used in DB.
    'type' => 'lrs',        // elastic or lrs

    'endpoint' => 'http://trax.test/trax/ws/xapi',  // LRS endpoint
    'username' => '',       // LRS Basic HTTP username
    'password' => '',       // LRS Basic HTTP password

    'batch_size' => 100,    // Number of statements per POST request
    'max_batches' => 10,    // Number of requests per command (or CRON task)
    'max_attempts' => 3,    // Number of tries when an error occurs 
],
```

### ElasticSearch example

```
'elastic' => [

    'id' => 1,              // Define it, and don't change it. It is used in DB.
    'type' => 'elastic',    // elastic or lrs

    'hosts' => [            // ElasticSearch hosts
        'localhost:9200'    
    ],
    'username' => '',       // ElasticSearch Basic HTTP username
    'password' => '',       // ElasticSearch Basic HTTP password

    'batch_size' => 100,    // Number of statements per POST request
    'max_batches' => 10,    // Number of requests per command (or CRON task)
    'max_attempts' => 3,    // Number of tries when an error occurs 
],
```


## Commands

Configure your connections in `config/trax-sync.php`, then:

- `php artisan trax:push <connection>` to start or continue synchronization
- `php artisan trax:push <connection> --all` to restart synchronization


## License and copyright

Distributed under the [EUPL 1.2 license](https://eupl.eu/1.2/en/).

Copyright 2019 Sébastien Fraysse, http://fraysse.eu, <sebastien@fraysse.eu>.




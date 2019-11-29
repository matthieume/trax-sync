<?php

return [

    'enabled' => true,

    'connections' => [

        'trax' => [

            'id' => 0,          // Never change this value. It is used in DB.
            'type' => 'lrs',

            'endpoint' => 'http://trax.home/trax/ws/xapi',
            'username' => 'testsuite',
            'password' => 'password',

            'batch_size' => 100,
            'max_attempts' => 3,
        ],
    
        'elastic' => [

            'id' => 1,          // Never change this value. It is used in DB.
            'type' => 'elastic',

            'hosts' => [
                'localhost:9200'
            ],
            'username' => '',
            'password' => '',

            'batch_size' => 100,
            'max_attempts' => 3,
        ],

        'cloud' => [

            'id' => 2,          // Never change this value. It is used in DB.
            'type' => 'elastic',

            'hosts' => [
                'https://8f5883f5de314c09a951839d5d7e12f1.eu-central-1.aws.cloud.es.io:9243'
            ],
            'username' => 'elastic',
            'password' => 'n3KoaoDD1bV3tlg8YT2XyarQ',

            'batch_size' => 100,
            'max_attempts' => 3,
        ]
    ]

];

<?php

return [

    'enabled' => true,

    'connections' => [

        'trax' => [

            'id' => 0,          // Used in DB!
            'type' => 'lrs',

            'endpoint' => 'http://trax.home/trax/ws/xapi',
            'username' => 'testsuite',
            'password' => 'password',

            'batch_size' => 100,
            'max_batches' => 100,
            'max_attempts' => 3,
        ],
    
        'elastic' => [

            'id' => 1,          // Used in DB!
            'type' => 'elastic',

            'hosts' => [
                'localhost:9200'
            ],
            'username' => '',
            'password' => '',

            'batch_size' => 100,
            'max_batches' => 100,
            'max_attempts' => 3,
        ]
    ]

];

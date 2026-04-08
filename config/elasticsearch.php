<?php
// config/elasticsearch.php
return [
    'hosts'    => explode(',', env('ELASTICSEARCH_HOSTS', 'http://localhost:9200')),
    'user'     => env('ELASTICSEARCH_USER', ''),
    'password' => env('ELASTICSEARCH_PASSWORD', ''),
];

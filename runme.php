<?php

require_once "vendor/autoload.php";

use WArslett\TweetSyncDoctrine\ConsoleRunner;

ConsoleRunner::configureFromArray([
    'warslett_tweet_sync.consumer_key' => 'qf661I5KNpMef4AeamqgGsoyI',
    'warslett_tweet_sync.consumer_secret' => 'tfSWKLmmfeUbFX1cXFjgqFD8VANr2iYHIj1UYnQgJDhORMLmTo',
    'warslett_tweet_sync.oauth_access_token' => '751766475531292672-tC4BX2dxaLatLyEYO5paqwsGMjU43Rd',
    'warslett_tweet_sync.oauth_access_token_secret' => 'Ki36SdvHI6az7rV8EAnktWnXjI9WX2cDg2Fdd2g5nSCmO',
    'database.config' => [
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__ . "/behaviour/db.sqlite"
    ]
])->run();
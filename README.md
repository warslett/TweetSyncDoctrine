TweetSync
=======
A tool for synchronising a twitter users recent tweets with a local database

Before you start:
-----------
You will need to create an application with https://apps.twitter.com/ to get your own consumer key and consumer secret. You will also need to generate an oauth access token and oauth secret with read access to your twitter account.

Installation:
-----------
`composer require warslett/tweet-sync-doctrine`

Setup with Symfony
-----------
Add the following parameters to your parameters.yml:
```yaml
parameters:
  warslett_tweet_sync.consumer_key: YOUR_CONSUMER_KEY
  warslett_tweet_sync.consumer_secret: YOUR_CONSUMER_SECRET
  warslett_tweet_sync.oauth_access_token: YOUR_OAUTH_ACCESS_TOKEN
  warslett_tweet_sync.oauth_access_token_secret: YOUR_OAUTH_ACCESS_TOKEN_SECRET
```

Add the following resource to the imports at the top of your config.yml;
```yaml
imports:
    - { resource: ../../vendor/warslett/tweet-sync-doctrine/src/Resources/config/services_core.yml }
```

Finally add the doctrine mapping to your doctrine configuration:
```yaml
doctrine:
  orm:
    entity_managers:
      default:
        mappings:
          WArslettTweetSync:
            mapping: true
            type: yml
            dir: %kernel.root_dir%/../vendor/warslett/tweet-sync-doctrine/src/Resources/config/doctrine
            prefix: WArslett\TweetSync\Model
```

Now clear your cache `php app/console cache:clear` and update your database schema `php app/console doctrine:schema:update --force`

Setup without Symfony:
-----------

Create your own Console Runner wherever you want:

```php
#!/usr/bin/env php
<?php
# app/console

// replace with the path to your own autoloader
require __DIR__.'/vendor/autoload.php';

use WArslett\TweetSyncDoctrine\ConsoleRunner;

ConsoleRunner::configureFromArray([
    'warslett_tweet_sync.consumer_key' => 'YOUR CONSUMER KEY',
    'warslett_tweet_sync.consumer_secret' => 'YOUR CONSUMER SECRET',
    'warslett_tweet_sync.oauth_access_token' => 'YOUR OAUTH ACCESS TOKEN',
    'warslett_tweet_sync.oauth_access_token_secret' => 'YOUR OAUTH ACCESS TOKEN SECRET',
    'database.config' => [
        // Replace with your own db params
        // See: http://doctrine-orm.readthedocs.org/projects/doctrine-dbal/en/latest/reference/configuration.html
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__ . "/db.sqlite"
    ]
])->run();
```

Then run init to initialise the tables in the db: `php app/console tweetsync:init` (replace app/console with the location of your console runner)

Usage:
-----------
To synchronise a user "BBCBreaking": `php app/console tweetsync:user BBCBreaking` (replace app/console with the location of your console runner)
Add to the crontab for a regular sync: http://crontab.org/

To get tweets that have been synchronised for use in your application:
```php
<?php

// replace with the path to your own autoloader
require __DIR__.'/vendor/autoload.php';

//In symfony
$tweetRepository = $entityManager->getRepository('WArslett\TweetSync\Model\Tweet');

//Not in smyfony
$persistenceService = \WArslett\TweetSyncDoctrine\ORM\Doctrine\TweetPersistenceService::create([
    // Replace with your own db params
    // See: http://doctrine-orm.readthedocs.org/projects/doctrine-dbal/en/latest/reference/configuration.html
    'driver'   => 'pdo_sqlite',
    'path'     => __DIR__ . "/db.sqlite"
]);
$tweetRepository = $persistenceService->getTweetRepository();

$tweets = $tweetRepository->findByUsername('BBCBreaking');

foreach($tweets as $tweet) {
    print($tweet->getText());
}

```
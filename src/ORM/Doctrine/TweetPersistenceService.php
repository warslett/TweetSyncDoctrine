<?php

namespace WArslett\TweetSyncDoctrine\ORM\Doctrine;


use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\Tools\Setup;
use WArslett\TweetSync\Model\Tweet;
use WArslett\TweetSync\Model\TweetPersistenceService as TweetPersistenceServiceInterface;
use WArslett\TweetSync\Model\TwitterUser;

/**
 * Class DoctrineTweetPersistenceService
 * @package WArslett\TweetSync\ORM\Doctrine
 */
class TweetPersistenceService implements TweetPersistenceServiceInterface
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Tweet $tweet
     * @return mixed
     */
    public function persistTweet(Tweet $tweet)
    {
        $this->em->persist($tweet);
        $this->em->flush($tweet);
    }

    /**
     * @param Tweet $tweet
     */
    public function ensureTweetUpdated(Tweet $tweet)
    {
        $this->em->flush($tweet);
    }

    /**
     * @param TwitterUser $twitterUser
     */
    public function persistTwitterUser(TwitterUser $twitterUser)
    {
        $this->em->persist($twitterUser);
        $this->em->flush($twitterUser);
    }

    /**
     * @param TwitterUser $twitterUser
     */
    public function ensureTwitterUserUpdated(TwitterUser $twitterUser)
    {
        $this->em->flush($twitterUser);
    }

    /**
     * @return TwitterUserRepository
     */
    public function getTwitterUserRepository()
    {
        return $this->em->getRepository('WArslett\TweetSync\Model\TwitterUser');
    }

    /**
     * @return TweetRepository
     */
    public function getTweetRepository()
    {
        return $this->em->getRepository('WArslett\TweetSync\Model\Tweet');
    }

    /**
     * @param $dbParams
     * @return TweetPersistenceService
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public static function create($dbParams)
    {
        $yamlDriver = new SimplifiedYamlDriver([
            __DIR__ . "/../../Resources/config/doctrine" => 'WArslett\TweetSync\Model'
        ]);

        $config = Setup::createConfiguration();
        $config->setMetadataDriverImpl($yamlDriver);
        $config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
        $entityManager = EntityManager::create($dbParams, $config);

        return new self($entityManager);
    }
}

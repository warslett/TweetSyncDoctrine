<?php

namespace WArslett\TweetSyncDoctrine\Console;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WArslett\TweetSync\Remote\TweetSync;

class SyncUserCommand extends Command
{

    /**
     * @var TweetSync
     */
    private $sync;

    public function __construct(TweetSync $sync)
    {
        parent::__construct();
        $this->sync = $sync;
    }

    protected function configure()
    {
        $this
            ->setName('tweetsync:user')
            ->setDescription('Sync recent tweets by a specified user')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'The twitter username to sync eg. BBCBreaking'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sync->syncAllForUser($input->getArgument('username'));
    }
}
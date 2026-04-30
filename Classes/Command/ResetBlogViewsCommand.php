<?php

namespace NITSAN\NsBlogSystem\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class ResetBlogViewsCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Reset all blog views to 0 (monthly)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_nsblogsystem_domain_model_blog');

        $queryBuilder = $connection->createQueryBuilder();

        $affectedRows = $queryBuilder
            ->update('tx_nsblogsystem_domain_model_blog')
            ->set('views', 0)
            ->executeStatement();

        $output->writeln("Reset views for $affectedRows blog(s)");

        return Command::SUCCESS;
    }
}
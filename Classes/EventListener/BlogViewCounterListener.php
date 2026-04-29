<?php

namespace NITSAN\NsBlogSystem\EventListener;

use NITSAN\NsBlogSystem\Event\AfterBlogViewedEvent;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BlogViewCounterListener
{
    public function __invoke(AfterBlogViewedEvent $event): void
    {
        $blog = $event->getBlog();
        $blogUid = $blog->getUid();

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_nsblogsystem_domain_model_blog');

        $connection->executeStatement(
            'UPDATE tx_nsblogsystem_domain_model_blog 
             SET views = views + 1 
             WHERE uid = ?',
            [$blogUid]
        );
    }
}
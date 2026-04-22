<?php

namespace NITSAN\NsBlogSystem\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class BlogRepository extends Repository
{
    public function findBlogs($limit, $sortOrder, $storagePid)
    {
        $query = $this->createQuery();
        $querySettings = $query->getQuerySettings();

        // Only apply storage PID if it exists
        if (!empty($storagePid)) {
            $querySettings->setStoragePageIds([(int)$storagePid]);
        }

        // Sorting
        $query->setOrderings([
            'uid' => $sortOrder === 'ASC'
                ? \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
                : \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
        ]);

        // Limit
        if (!empty($limit)) {
            $query->setLimit((int)$limit);
        }

        return $query->execute();
    }
    public function findByTitle($title)
    {
        $query = $this->createQuery();

        $querySettings = $query->getQuerySettings();
        $querySettings->setRespectStoragePage(false);

        $query->matching(
            $query->like('title', '%' . $title . '%')
        );

        return $query->execute();
    }
}

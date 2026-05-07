<?php

declare(strict_types=1);

namespace NITSAN\NsBlogSystem\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

use NITSAN\NsBlogSystem\Domain\Model\Comment;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * This file is part of the "blog_system" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 
 */

/**
 * Blog
 */
class Blog extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * @var ObjectStorage<FileReference>
     */
    protected ObjectStorage $images;

    public function __construct()
    {
        $this->comments = new ObjectStorage();
        $this->images = new ObjectStorage();
    }

    /**
     * createddate
     *
     * @var \DateTime
     */
    protected $createddate = null;

    /**
     * @var ObjectStorage<\NITSAN\NsBlogSystem\Domain\Model\Comment>
     */
    protected ObjectStorage $comments;

    public function initializeObject(): void
    {
        $this->comments = $this->comments ?? new ObjectStorage();
        $this->images = $this->images ?? new ObjectStorage();
    }
    
    
    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Returns the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    /**
     * @param ObjectStorage<FileReference> $images
     */
    public function setImages(ObjectStorage $images): void
    {
        $this->images = $images;
    }

    public function addImage(FileReference $image): void
    {
        $this->images->attach($image);
    }

    public function removeImage(FileReference $image): void
    {
        $this->images->detach($image);
    }

    /**
     * Returns the createddate
     *
     * @return \DateTime
     */
    public function getCreateddate()
    {
        return $this->createddate;
    }

    /**
     * Sets the createddate
     *
     * @param \DateTime $createddate
     * @return void
     */
    public function setCreateddate(\DateTime $createddate)
    {
        $this->createddate = $createddate;
    }
    /**
     * views
     *
     * @var int
     */
    protected $views = 0;
    /**
     * Returns the comments
     *
     * @return ObjectStorage<Comment>
     */
    public function getComments(): ObjectStorage
    {
        return $this->comments;
    }
    /**
     * Adds a comment
     *
     * @param Comment $comment
     * @return void
     */
    public function addComment(Comment $comment): void
    {
        $this->comments->attach($comment);
    }

    /**
     * Removes a comment
     *
     * @param Comment $comment
     * @return void
     */
    public function removeComment(Comment $comment): void
    {
        $this->comments->detach($comment);
    }
    /**
     * Returns the views
     *
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * Sets the views
     *
     * @param int $views
     * @return void
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }
}

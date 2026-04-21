<?php

declare(strict_types=1);

namespace NITSAN\NsBlogSystem\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

use NITSAN\NsBlogSystem\Domain\Model\Comment;

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
     * images
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $images = null;

    /**
     * createddate
     *
     * @var \DateTime
     */
    protected $createddate = null;

    /**
     * comments
     *
     * @var ObjectStorage<Comment>
     */
    protected $comments = null;

    public function initializeObject(): void
    {
        if ($this->comments === null) {
            $this->comments = new ObjectStorage();
        }
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
     * Returns the images
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $images
     * @return void
     */
    public function setImages(\TYPO3\CMS\Extbase\Domain\Model\FileReference $images)
    {
        $this->images = $images;
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
}

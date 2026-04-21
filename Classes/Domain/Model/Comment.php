<?php

namespace NITSAN\NsBlogSystem\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

use NITSAN\NsBlogSystem\Domain\Model\Blog;

class Comment extends AbstractEntity
{
    protected string $name = '';

    protected string $email = '';

    protected string $comment = '';

    protected bool $approved = false;

    protected ?Blog $blog = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): void
    {
        $this->blog = $blog;
    }
}
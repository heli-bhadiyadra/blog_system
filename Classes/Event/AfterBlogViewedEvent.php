<?php

namespace NITSAN\NsBlogSystem\Event;

use NITSAN\NsBlogSystem\Domain\Model\Blog;

class AfterBlogViewedEvent
{
    protected Blog $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function getBlog(): Blog
    {
        return $this->blog;
    }
}
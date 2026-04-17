<?php

declare(strict_types=1);

namespace NITSAN\NsBlogSystem\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use NITSAN\NsBlogSystem\Domain\Repository\BlogRepository;
use Psr\Http\Message\ResponseInterface;
use NITSAN\NsBlogSystem\Domain\Model\Blog;

class BlogController extends ActionController
{
    protected BlogRepository $blogRepository;

    public function injectBlogRepository(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function listAction(): ResponseInterface
    {	
        $limit = $this->settings['limit'] ?? 5;
        $sortOrder = $this->settings['sortOrder'] ?? 'DESC';
        $storagePid = $this->settings['storagePid'] ?? null;

        $blogs = $this->blogRepository->findBlogs($limit, $sortOrder, $storagePid);

        $this->view->assign('blogs', $blogs);

        return $this->htmlResponse();
    
    }

    public function showAction(Blog $blog): ResponseInterface
    {
        $this->view->assign('blog', $blog);
        return $this->htmlResponse();
    }
}

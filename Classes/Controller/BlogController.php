<?php

declare(strict_types=1);

namespace NITSAN\NsBlogSystem\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use NITSAN\NsBlogSystem\Domain\Repository\BlogRepository;
use Psr\Http\Message\ResponseInterface;
use NITSAN\NsBlogSystem\Domain\Model\Blog;

use NITSAN\NsBlogSystem\Domain\Model\Comment;
use NITSAN\NsBlogSystem\Domain\Repository\CommentRepository;

class BlogController extends ActionController
{
    protected BlogRepository $blogRepository;

    protected CommentRepository $commentRepository;


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
         $this->view->assignMultiple([
            'blog' => $blog,
            'comment' => new \NITSAN\NsBlogSystem\Domain\Model\Comment()
        ]);
        return $this->htmlResponse();
    }
    public function injectCommentRepository(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function initializeCreateCommentAction(): void
    {
        $this->arguments->getArgument('comment')
            ->getPropertyMappingConfiguration()
            ->allowAllProperties();
    }

    public function createCommentAction(Comment $comment): ResponseInterface
    {
        $blogUid = (int)$this->request->getArgument('blog');
        $blog = $this->blogRepository->findByIdentifier($blogUid);

        $comment->setBlog($blog);
        $comment->setApproved(false);

        $this->commentRepository->add($comment);

        return $this->redirect('show', null, null, [
            'blog' => $blog->getUid()
        ]);
    }
    public function filterAction(): \Psr\Http\Message\ResponseInterface
    {
    
        $title = $this->request->hasArgument('title')
            ? $this->request->getArgument('title')
            : '';

        if ($title !== '') {
            $blogs = $this->blogRepository->findByTitle($title);
        } else {
            $blogs = $this->blogRepository->findAll();
        }

        $this->view->assignMultiple([
            'blogs' => $blogs,
            'showReadMore' => 0
        ]);
        
        return $this->htmlResponse(
            $this->view->renderPartial('Blog/BlogList', null, ['blogs' => $blogs])
        );
    }
    
    
}

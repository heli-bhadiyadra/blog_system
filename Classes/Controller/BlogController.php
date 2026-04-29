<?php

declare(strict_types=1);

namespace NITSAN\NsBlogSystem\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use NITSAN\NsBlogSystem\Domain\Repository\BlogRepository;
use Psr\Http\Message\ResponseInterface;
use NITSAN\NsBlogSystem\Domain\Model\Blog;

use NITSAN\NsBlogSystem\Domain\Model\Comment;
use NITSAN\NsBlogSystem\Domain\Repository\CommentRepository;

use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;

use Psr\EventDispatcher\EventDispatcherInterface;
use NITSAN\NsBlogSystem\Event\AfterBlogViewedEvent;

use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BlogController extends ActionController
{
    protected BlogRepository $blogRepository;

    protected CommentRepository $commentRepository;

    //Event
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
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

        //Pagination
        $blogsArray = $blogs->toArray();

        $currentPage = $this->request->hasArgument('currentPage')
            ? (int)$this->request->getArgument('currentPage')
            : 1;

        $itemsPerPage = 2;

        $paginator = new ArrayPaginator($blogsArray, $currentPage, $itemsPerPage);
        $pagination = new SimplePagination($paginator);

        $this->view->assignMultiple([
            'blogs' => $paginator->getPaginatedItems(),
            'paginator' => $paginator,
            'pagination' => $pagination
        ]);
        
        return $this->htmlResponse();
    
    }

    public function showAction(Blog $blog): ResponseInterface
    {
        //Event
        $event = new AfterBlogViewedEvent($blog);

        $this->eventDispatcher->dispatch($event);

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

        \TYPO3\CMS\Core\Utility\DebugUtility::debug('MAIL FUNCTION CALLED');

        // SEND EMAIL TO ADMIN
        $mail = GeneralUtility::makeInstance(FluidEmail::class);
        
        $mail
            ->to('admin@example.com') 
            ->from('noreply@example.com')
            ->subject('New Comment Posted')
            ->format('html')
            ->setTemplate('CommentNotification')
            ->assignMultiple([
                'blogTitle' => $blog->getTitle(),
                'name' => $comment->getName(),
                'email' => $comment->getEmail(),
                'comment' => $comment->getComment()
            ]);

        GeneralUtility::makeInstance(MailerInterface::class)->send($mail);

        return $this->redirect('show', null, null, [
            'blog' => $blog->getUid()
        ]);
    }
    //Implement filtering by title
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

        // convert to array
        $blogsArray = is_object($blogs) ? $blogs->toArray() : [];

        // get current page
        $currentPage = $this->request->hasArgument('filterPage')
            ? (int)$this->request->getArgument('filterPage')
            : 1;

        $itemsPerPage = 2;

        $paginator = new ArrayPaginator($blogsArray, $currentPage, $itemsPerPage);
        $pagination = new SimplePagination($paginator);

        return $this->htmlResponse(
            $this->view->renderPartial('Blog/BlogList', null, [
                'blogs' => $paginator->getPaginatedItems(),
                'pagination' => $pagination,
                'paginator' => $paginator,
                'showReadMore' => 0,
                'title' => $title
            ])
        );
    }
    
    
}

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

use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;

class BlogController extends ActionController
{
    protected BlogRepository $blogRepository;

    protected CommentRepository $commentRepository;


    protected EventDispatcherInterface $eventDispatcher;

    public function injectEventDispatcher(EventDispatcherInterface $eventDispatcher):void
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
        //Core Event
        $this->addFlashMessage(
            'Blog "' . $blog->getTitle() . '" opened successfully!',
            '',
            ContextualFeedbackSeverity::INFO
        );
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

        // Save comment
        $this->commentRepository->add($comment);
        
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class
        )->persistAll();
        
        // View Mail using Fluid Template
        $view = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Fluid\View\StandaloneView::class
        );

        $view->setTemplatePathAndFilename(
            \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
                'EXT:ns_blog_system/Resources/Private/Templates/Email/CommentNotification.html'
            )
        );

        $view->assignMultiple([
            'blogTitle' => $blog->getTitle(),
            'name' => $comment->getName(),
            'email' => $comment->getEmail(),
            'comment' => $comment->getComment()
        ]);

        $htmlBody = $view->render();

        // Send mail
        $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Mail\FluidEmail::class
        );

        $mail
            ->to('admin@example.com')
            ->from('noreply@example.com')
            ->subject('New Comment Added')
            ->html($htmlBody);

        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Mail\MailerInterface::class
        )->send($mail);

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

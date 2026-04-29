<?php

namespace NITSAN\NsBlogSystem\Controller\Backend;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use NITSAN\NsBlogSystem\Domain\Repository\BlogRepository;
use NITSAN\NsBlogSystem\Domain\Model\Blog;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class BlogController extends ActionController
{

    protected BlogRepository $blogRepository;

    protected PageRenderer $pageRenderer;

    public function injectBlogRepository(BlogRepository $blogRepository): void
    {
        $this->blogRepository = $blogRepository;
    }

    public function initializeAction(): void
    {
        $querySettings = $this->blogRepository->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $querySettings->setIgnoreEnableFields(true);
    }

    public function listAction(): ResponseInterface
    {

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadJavaScriptModule('@ns_blog_system/delete-confirm');

        $blogs = $this->blogRepository->findAll();
        $this->view->assign('blogs', $blogs);

        return $this->htmlResponse();
    
    }

    public function newAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function createAction(Blog $blog): ResponseInterface
    {
        $this->blogRepository->add($blog);
        return $this->redirect('list');
    }

    public function editAction(Blog $blog): ResponseInterface
    {
        $this->view->assign('blog', $blog);
        return $this->htmlResponse();
    }

    public function updateAction(Blog $blog): ResponseInterface
    {
        $this->blogRepository->update($blog);
        $this->addFlashMessage(
            'Blog "' . $blog->getTitle() . '" updated successfully.',
        );
        return $this->redirect('list');
    }
    
    public function deleteAction(int $blog): ResponseInterface
    {
        $blogObject = $this->blogRepository->findByIdentifier($blog);

        if ($blogObject) {
            $this->blogRepository->remove($blogObject);
            $this->addFlashMessage('Blog deleted successfully.');
        }

        return $this->redirect('list');
    }
}
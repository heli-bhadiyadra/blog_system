<?php

namespace NITSAN\NsBlogSystem\Controller\Backend;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use NITSAN\NsBlogSystem\Domain\Repository\BlogRepository;
use NITSAN\NsBlogSystem\Domain\Model\Blog;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;

use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
        $this->view->assign('blog', new Blog());
        return $this->htmlResponse();
    }

    public function initializeCreateAction(): void
    {
        if ($this->arguments->hasArgument('blog')) {
            $propertyMappingConfiguration = $this->arguments
                ->getArgument('blog')
                ->getPropertyMappingConfiguration();

            $propertyMappingConfiguration->allowAllProperties();
        }
    }

    public function createAction(Blog $blog): ResponseInterface
    {
         debug($blog);
        $uploadedFiles = $this->request->getUploadedFiles();

        if (!empty($uploadedFiles['images'])) {

            $uploadedFile = $uploadedFiles['images'];

            if ($uploadedFile->getError() === 0) {

                $storage = GeneralUtility::makeInstance(StorageRepository::class)
                    ->findByUid(1);

                $folder = $storage->getRootLevelFolder();

                $file = $storage->addUploadedFile(
                    $uploadedFile,
                    $folder,
                    $uploadedFile->getClientFilename()
                );

                $sysFileReference = GeneralUtility::makeInstance(CoreFileReference::class, [
                    'uid_local' => $file->getUid(),
                    'uid_foreign' => 0,
                    'tablenames' => 'tx_nsblogsystem_domain_model_blog',
                    'fieldname' => 'images',
                    'pid' => $blog->getPid(),
                ]);

                $fileReference = new FileReference();
                $fileReference->setOriginalResource($sysFileReference);

                $images = new ObjectStorage();
                $images->attach($fileReference);

                $blog->setImages($images);
            }
        }
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
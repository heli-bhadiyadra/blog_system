<?php

namespace NITSAN\NsBlogSystem\Xclass;

use TYPO3\CMS\Backend\Template\Components\ButtonBar as CoreButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\ButtonInterface;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use TYPO3\CMS\Core\Page\PageRenderer;

class ButtonBar extends CoreButtonBar
{
    public function addButton(
        ButtonInterface $button,
        $buttonPosition = self::BUTTON_POSITION_LEFT,
        $buttonGroup = 1
    ) {
        parent::addButton($button, $buttonPosition, $buttonGroup);

        static $added = false;

        if (!$added) {
 
            $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
            $pageRenderer->loadJavaScriptModule('@ns_blog_system/backend');
            
            $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

            $myButton = $this->makeLinkButton();

            $myButton
                ->setTitle('My Button')
                ->setHref('#')
                ->setDataAttributes([
                    'my-button' => '1'
                ])
                ->setIcon(
                    $iconFactory->getIcon('actions-document-new', Icon::SIZE_SMALL)
                )
                ->setShowLabelText(true);

            parent::addButton($myButton, self::BUTTON_POSITION_LEFT, 1);

            $added = true;
        }
    }
}
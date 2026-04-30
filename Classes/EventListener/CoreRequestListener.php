<?php

namespace NITSAN\NsBlogSystem\EventListener;

use TYPO3\CMS\Extbase\Mvc\Event\AfterRequestDispatchedEvent;

class CoreRequestListener
{
    public function __invoke(AfterRequestDispatchedEvent $event): void
    {
        $request = $event->getRequest();

        // Only target YOUR extension
        if ($request->getControllerExtensionName() !== 'NsBlogSystem') {
            return;
        }

        // Only Blog controller
        if ($request->getControllerName() !== 'Blog') {
            return;
        }

        // Only show action (detail page)
        if ($request->getControllerActionName() !== 'show') {
            return;
        }

        // Debug log
        error_log('CORE EVENT: Blog detail opened');
    }
}
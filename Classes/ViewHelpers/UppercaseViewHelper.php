<?php

namespace NITSAN\NsBlogSystem\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class UppercaseViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument(
            'text',
            'string',
            'Text to convert to uppercase',
            true
        );
    }

    public function render()
    {
        return strtoupper($this->arguments['text']);
    }
}
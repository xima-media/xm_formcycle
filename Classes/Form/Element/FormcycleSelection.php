<?php

namespace Xima\XmFormcycle\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class FormcycleSelection extends AbstractFormElement
{

    public function render()
    {
        $resultArray = $this->initializeResultArray();
        $resultArray['html'] = 'hello world';
        return $resultArray;
    }
}

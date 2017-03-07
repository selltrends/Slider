<?php

namespace Atopt\Slider\Controller\Adminhtml\Slider;

class NewAction extends \Atopt\Slider\Controller\Adminhtml\Slider
{
    /**
     * Create new slider action
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute(){
        //Forward to the edit action
        $resultForward = $this->_resultForwardFactory->create();
        $resultForward->forward('edit');
        return $resultForward;
    }
}
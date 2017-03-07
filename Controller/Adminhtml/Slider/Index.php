<?php

namespace Atopt\Slider\Controller\Adminhtml\Slider;

class Index extends \Atopt\Slider\Controller\Adminhtml\Slider {
    /**
     * Slider index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute(){
        if ($this->getRequest()->getQuery('ajax')) {
            $resultForward = $this->_resultForwardFactory->create();
            $resultForward->forward('grid');
            return $resultForward;
        }
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Atopt_Slider::admin');
        $resultPage->addBreadcrumb(__('Sliders'), __('Sliders'));
        $resultPage->addBreadcrumb(__('Manage Sliders'), __('Manage Sliders'));
        return $resultPage;
    }
}

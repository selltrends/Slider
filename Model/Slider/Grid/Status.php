<?php

namespace Atopt\Slider\Model\Slider\Grid;

class Status implements \Magento\Framework\Option\ArrayInterface {
    /**
     * To option slider statuses array
     * @return array
     */
    public function toOptionArray(){
        return \Atopt\Slider\Model\ProductSlider::getStatusArray();
    }
}
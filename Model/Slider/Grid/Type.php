<?php

namespace Atopt\Slider\Model\Slider\Grid;

class Type implements \Magento\Framework\Data\OptionSourceInterface{
    /**
     * To option slider types array
     * @return array
     */
    public function toOptionArray(){
        return \Atopt\Slider\Model\ProductSlider::getSliderTypeArray();
    }
}
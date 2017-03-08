<?php

namespace Atopt\Slider\Model\Slider\Grid;
/**
 * To option slider locations array
 * @return array
 */
class Location implements \Magento\Framework\Option\ArrayInterface{
    public function toOptionArray(){
        return \Atopt\Slider\Model\ProductSlider::getSliderGridLocations();
    }
}
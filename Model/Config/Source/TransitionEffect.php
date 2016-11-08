<?php

namespace Atopt\Slider\Model\Config\Source;

class TransitionEffect implements \Magento\Framework\Option\ArrayInterface
{
	protected  $_blockModel;

	/**
	 * @param \Magento\Cms\Model\Block $blockModel
	 */
	public function __construct(
			\Magento\Cms\Model\Block $blockModel
			) {
				$this->_groupModel = $blockModel;
	}

	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		$effects = [
				['label'=>__('Fade'),'value'=>'fade'],
				['label'=>__('Back Slide'),'value'=>'backSlide'],
				['label'=>__('Go Down'),'value'=>'goDown'],
				['label'=>__('Fade Up'),'value'=>'fadeUp'],
		];
		array_unshift($effects, array(
				'value' => '',
				'label' => '',
		));
		return $effects;
	}
}
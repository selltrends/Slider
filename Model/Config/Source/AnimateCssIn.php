<?php

namespace Atopt\Slider\Model\Config\Source;

class AnimateCssIn implements \Magento\Framework\Option\ArrayInterface
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
				['label' =>__('Disable'),'value'=>0],
				['label'=>__('Attention Seekers'),'value'=>[
						['label'=>'bounce', 'value'=>'bounce'],
						['label'=>'flash', 'value'=>'flash'],
						['label'=>'pulse', 'value'=>'pulse'],
						['label'=>'rubberBand', 'value'=>'rubberBand'],
						['label'=>'shake', 'value'=>'shake'],
						['label'=>'swing', 'value'=>'swing'],
						['label'=>'tada', 'value'=>'tada'],
						['label'=>'wobble', 'value'=>'wobble'],
						['label'=>'jello', 'value'=>'jello']
				]],
				['label'=>__('Bouncing Entrances'),'value'=>[
						['label'=>'bounceIn', 'value'=>'bounceIn'],
						['label'=>'bounceInDown', 'value'=>'bounceInDown'],
						['label'=>'bounceInLeft', 'value'=>'bounceInLeft'],
						['label'=>'bounceInRight', 'value'=>'bounceInRight'],
						['label'=>'bounceInUp', 'value'=>'bounceInUp']
				]],
				['label'=>__('Fading Entrances'),'value'=>[
						['label'=>'fadeIn', 'value'=>'fadeIn'],
						['label'=>'fadeInDown', 'value'=>'fadeInDown'],
						['label'=>'fadeInDownBig', 'value'=>'fadeInDownBig'],
						['label'=>'fadeInLeft', 'value'=>'fadeInLeft'],
						['label'=>'fadeInLeftBig', 'value'=>'fadeInLeftBig'],
						['label'=>'fadeInRight', 'value'=>'fadeInRight'],
						['label'=>'fadeInRightBig', 'value'=>'fadeInRightBig'],
						['label'=>'fadeInUp', 'value'=>'fadeInUp'],
						['label'=>'fadeInUpBig', 'value'=>'fadeInUpBig']
				]],
				['label'=>__('Flippers'),'value'=>[
						['label'=>'flip', 'value'=>'flip'],
						['label'=>'flipInX', 'value'=>'flipInX'],
						['label'=>'flipInY', 'value'=>'flipInY']
				]],
				['label'=>__('Lightspeed'),'value'=>[
						['label'=>'lightSpeedIn', 'value'=>'lightSpeedIn']
				]],
				['label'=>__('Rotating Entrances'),'value'=>[
						['label'=>'rotateIn', 'value'=>'rotateIn'],
						['label'=>'rotateInDownLeft', 'value'=>'rotateInDownLeft'],
						['label'=>'rotateInDownRight', 'value'=>'rotateInDownRight'],
						['label'=>'rotateInUpLeft', 'value'=>'rotateInUpLeft'],
						['label'=>'rotateInUpRight', 'value'=>'rotateInUpRight']
				]],
				['label'=>__('Zoom Entrances'),'value'=>[
						['label'=>'zoomIn', 'value'=>'zoomIn'],
						['label'=>'zoomInDown', 'value'=>'zoomInDown'],
						['label'=>'zoomInLeft', 'value'=>'zoomInLeft'],
						['label'=>'zoomInRight', 'value'=>'zoomInRight'],
						['label'=>'zoomInUp', 'value'=>'zoomInUp']
				]],
				['label'=>__('Specials'),'value'=>[
						['label'=>'hinge', 'value'=>'hinge'],
						['label'=>'rollIn', 'value'=>'rollIn'],
						['label'=>'rollOut', 'value'=>'rollOut']
				]]
		];
		array_unshift($effects, array(
				'value' => '',
				'label' => '',
		));
		return $effects;
	}
}
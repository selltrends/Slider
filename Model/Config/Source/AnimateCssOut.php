<?php

namespace Atopt\Slider\Model\Config\Source;

class AnimateCssOut implements \Magento\Framework\Option\ArrayInterface
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
				['label'=>__('Bouncing Exits'),'value'=>[
						['label'=>'bounceOut', 'value'=>'bounceOut'],
						['label'=>'bounceOutDown', 'value'=>'bounceOutDown'],
						['label'=>'bounceOutLeft', 'value'=>'bounceOutLeft'],
						['label'=>'bounceOutRight', 'value'=>'bounceOutRight'],
						['label'=>'bounceOutUp', 'value'=>'bounceOutUp']
				]],
				['label'=>__('Fading Exits'),'value'=>[
						['label'=>'fadeOut', 'value'=>'fadeOut'],
						['label'=>'fadeOutDown', 'value'=>'fadeOutDown'],
						['label'=>'fadeOutDownBig', 'value'=>'fadeOutDownBig'],
						['label'=>'fadeOutLeft', 'value'=>'fadeOutLeft'],
						['label'=>'fadeOutLeftBig', 'value'=>'fadeOutLeftBig'],
						['label'=>'fadeOutRight', 'value'=>'fadeOutRight'],
						['label'=>'fadeOutRightBig', 'value'=>'fadeOutRightBig'],
						['label'=>'fadeOutUp', 'value'=>'fadeOutUp'],
						['label'=>'fadeOutUpBig', 'value'=>'fadeOutUpBig']
				]],
				['label'=>__('Flippers'),'value'=>[
						['label'=>'flip', 'value'=>'flip'],
						['label'=>'flipOutX', 'value'=>'flipOutX'],
						['label'=>'flipOutY', 'value'=>'flipOutY']
				]],
				['label'=>__('Lightspeed'),'value'=>[
						['label'=>'lightSpeedOut', 'value'=>'lightSpeedOut']
				]],
				['label'=>__('Rotating Exits'),'value'=>[
						['label'=>'rotateOut', 'value'=>'rotateOut'],
						['label'=>'rotateOutDownLeft', 'value'=>'rotateOutDownLeft'],
						['label'=>'rotateOutDownRight', 'value'=>'rotateOutDownRight'],
						['label'=>'rotateOutUpLeft', 'value'=>'rotateOutUpLeft'],
						['label'=>'rotateOutUpRight', 'value'=>'rotateOutUpRight']
				]],
				['label'=>__('Sliding Exits'),'value'=>[
						['label'=>'slideOutUp', 'value'=>'slideOutUp'],
						['label'=>'slideOutDown', 'value'=>'slideOutDown'],
						['label'=>'slideOutLeft', 'value'=>'slideOutLeft'],
						['label'=>'slideOutRight', 'value'=>'slideOutRight']
				]],
				['label'=>__('Zoom Exits'),'value'=>[
						['label'=>'zoomOut', 'value'=>'zoomOut'],
						['label'=>'zoomOutDown', 'value'=>'zoomOutDown'],
						['label'=>'zoomOutLeft', 'value'=>'zoomOutLeft'],
						['label'=>'zoomOutRight', 'value'=>'zoomOutRight'],
						['label'=>'zoomOutUp', 'value'=>'zoomOutUp']
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
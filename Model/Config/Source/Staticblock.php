<?php

namespace Atopt\Slider\Model\Config\Source;

class Staticblock implements \Magento\Framework\Option\ArrayInterface
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
		$collection = $this->_groupModel->getCollection();
		$blocks = array();
		foreach ($collection as $_block) {
			$blocks[] = [
					'value' => $_block->getId(),
					'label' => $_block->getTitle()
			];
		}
		array_unshift($blocks, array(
				'value' => 'custom_content',
				'label' => 'Use Custom Content',
		));
		return $blocks;
	}
}
<?php

namespace Atopt\Slider\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	/**
	 * @var \Magento\Cms\Model\Template\FilterProvider
	 */
	protected $_filterProvider;

	public function __construct(
			\Magento\Framework\App\Helper\Context $context,
			\Magento\Cms\Model\Template\FilterProvider $filterProvider
			) {
				parent::__construct($context);
				$this->_filterProvider = $filterProvider;
	}

	public function filter($str)
	{
		$html = $this->_filterProvider->getPageFilter()->filter($str);
		return $html;
	}
}
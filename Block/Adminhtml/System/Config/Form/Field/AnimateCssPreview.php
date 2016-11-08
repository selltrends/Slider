<?php

namespace Atopt\Slider\Block\Adminhtml\System\Config\Form\Field;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class AnimateCssPreview extends Template implements RendererInterface
{
	/**
	 * @param  AbstractElement $element
	 * @return string
	 */
	public function render(AbstractElement $element)
	{
		$html = '<div class="btn-popup" onclick="showPopup(true)" style="color: #eb5202;cursor: pointer;width: 200px;margin-bottom: 20px;margin-left: 20%;font-weight:bold">Open Animate Preview</div>';
		$script = '';
		$script .= '<script>
			require(["jquery"],function(){
				jQuery(document).ready(function() {
					jQuery("body").append(\'<div class="animate-preview" style="position: fixed;z-index: 9999;top: -100%;width: 80%;height: 80%;left: 10%;right:10%"><div class="btn-preview-close" style="position: absolute;right: 0;padding: 5px;cursor: pointer;background: #eb5202;color: #FFF;"><span>Close</span></div><iframe style="width:100%;height:100%" src="http://daneden.github.io/animate.css"></iframe></div>\');
				});
				jQuery(".btn-popup").click(function(){
					jQuery(".animate-preview").stop().animate({"top":"15%"});
				});
				jQuery("body").on("click",".btn-preview-close",function(){
					jQuery(".animate-preview").stop().animate({"top":"-100%"});
				});
			});
		</script>';
		return $html.$script;
	}
}
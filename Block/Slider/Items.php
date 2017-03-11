<?php

namespace Atopt\Slider\Block\Slider;

class Items extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * Max number of products in slider
     */
    const MAX_PRODUCTS_COUNT = 20;
    protected $renderer;
    /**
     * Products collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productsCollectionFactory;
    /**
     * Product reports collection factory
     *
     * @var \Magento\Reports\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_reportsCollectionFactory;
    /**
     * Product slider factory
     *
     * @var \Atopt\Slider\Model\ProductSliderFactory
     */
    protected $_sliderFactory;
    /**
     * Product slider id
     *
     * @var int
     */
    protected $_sliderId;
    /**
     * Product slider model
     *
     * @var \Atopt\Slider\Model\ProductSlider
     */
    protected $_slider;
    /**
     * Events type factory
     *
     * @var \Magento\Reports\Model\Event\TypeFactory
     */
    protected $_eventTypeFactory;
    /**
     * Products visibility
     *
     * @var \Magento\Reports\Model\Event\TypeFactory
     */
    protected $_catalogProductVisibility;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * Product slider template
     */
    protected $_template = 'Atopt_Slider::slider/items.phtml';
    /**
     * @var
     */
    protected $_productsNumber;
    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;
    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportsCollectionFactory
     * @param \Atopt\Slider\Model\ProductsliderFactory $productsliderFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Reports\Model\Event\TypeFactory $eventTypeFactory
     * @param array $data
     */
    public function __construct
    (
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportsCollectionFactory,
        \Atopt\Slider\Model\ProductSliderFactory $productSliderFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Reports\Model\Event\TypeFactory $eventTypeFactory,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = []
    ){
        $this->_productCollectionFactory = $productsCollectionFactory;
        $this->_reportsCollectionFactory = $reportsCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_sliderFactory = $productSliderFactory;
        $this->_dateTime = $dateTime;
        $this->_eventTypeFactory = $eventTypeFactory;
        $this->_storeManager = $context->getStoreManager();
        $this->urlHelper = $urlHelper;
        parent::__construct($context,$data);
    }
    /**
     * @param $type
     * @return Collection|\Magento\Catalog\Model\ResourceModel\Product\Collection|string
     */
    public function getSliderProducts($type)
    {
        $collection = "";
        switch($type){
            case 'new':
                $collection =  $this->_getNewProducts($this->_productCollectionFactory->create());
                break;
            case 'bestsellers':
                $collection = $this->_getBestsellersProducts($this->_productCollectionFactory->create());
                break;
            case 'mostviewed':
                $collection =  $this->_getMostViewedProducts($this->_productCollectionFactory->create());
                break;
            case 'onsale':
                $collection =  $this->_getOnSaleProducts($this->_productCollectionFactory->create());
                break;
            case 'featured':
                $collection =  $this->_getSliderFeaturedProducts($this->_productCollectionFactory->create());
                break;
            case 'autorelated':
                $collection =  $this->_getAutoRelatedProducts($this->_productCollectionFactory->create());
                break;
        }
        return $collection;
    }
    /**
     * Get additional-featured slider products
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getSliderFeaturedProducts($collection)
    {
        $collection = $this->_addProductAttributesAndPrices($collection);
        $collection->getSelect()
                    ->join(['slider_products' => $collection->getTable('atopt_slider_products')],
                            'e.entity_id = slider_products.product_id AND slider_products.slider_id = '.$this->getSliderId(),
                            ['position'])
                    ->order('slider_products.position');
        $collection->addStoreFilter($this->getStoreId())
                    ->setPageSize($this->getProductsCount())
                    ->setCurPage(1);
        $this->_productsNumber = $this->getProductsCount() - $collection->count();
        return $collection;
    }
    /**
     * Get product slider items based on type
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getNewProducts($collection)
    {
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToFilter(
                'news_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()],
                'left')
            ->addAttributeToFilter(
                'news_to_date',
                [
                    'or' => [
                        0 => ['date' => true, 'from' => $this->getStartOfDayDate()],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ]
                ],
                'left')
            ->addAttributeToSort(
               'news_from_date',
               'desc')
            ->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1);
        return $collection;
    }
    /**
     * Get most viewed products
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     *
     * @return Collection
     */
    protected function _getMostViewedProducts($collection)
    {
        $eventTypes = $this->_eventTypeFactory->create()->getCollection();
        $reportCollection = $this->_reportsCollectionFactory->create();
        // Getting event type id for catalog_product_view event
        foreach ($eventTypes as $eventType) {
            if ($eventType->getEventName() == 'catalog_product_view') {
                $productViewEvent = (int)$eventType->getId();
                break;
            }
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection);
        $collection->getSelect()->reset()->from(
                    ['report_table_views' => $reportCollection->getTable('report_event')],
                    ['views' => 'COUNT(report_table_views.event_id)']
                )->join(
                    ['e' => $reportCollection->getProductEntityTableName()],
                    $reportCollection->getConnection()->quoteInto(
                        'e.entity_id = report_table_views.object_id',
                        $reportCollection->getProductAttributeSetId()
                    )
                )->where(
                    'report_table_views.event_type_id = ?',
                    $productViewEvent
                )->group(
                    'e.entity_id'
                )->order(
                    'views DESC'
                )->having(
                    'COUNT(report_table_views.event_id) > ?',
                    0
                );
        $collection->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1);
//            ->addViewsCount()
        return $collection;
    }
    /**
     * Get on sale slider products
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getOnSaleProducts($collection)
    {
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToFilter(
                'special_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()],
                'left')
            ->addAttributeToFilter(
                'special_to_date',
                [
                    'or' => [
                        0 => ['date' => true, 'from' => $this->getStartOfDayDate()],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ]
                ],
                'left')
            ->addAttributeToSort(
                'news_from_date',
                'desc')
            ->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1);
        return $collection;
    }
    /**
     * Get best selling products
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getBestsellersProducts($collection)
    {
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection);
        $collection->getSelect()
                    ->join(['bestsellers' => $collection->getTable('sales_bestsellers_aggregated_yearly')],
                                'e.entity_id = bestsellers.product_id AND bestsellers.store_id = '.$this->getStoreId(),
                                ['qty_ordered','rating_pos'])
                    ->order('rating_pos');
        $collection->addStoreFilter($this->getStoreId())
                    ->setPageSize($this->getProductsCount())
                    ->setCurPage(1);
        return $collection;
    }
    /**
     * @param $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getAutoRelatedProducts($collection)
    {
        $product = $this->getProduct();
        if(!$product){
            return;
        }
        $categories = $this->getProduct()->getCategoryIds();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection);
        $collection->addCategoriesFilter(['in' => $categories]);
        $collection->addStoreFilter($this->getStoreId())
                    ->setPageSize($this->getProductsCount())
                    ->setCurPage(1);
        $collection->addAttributeToFilter('entity_id', array('neq' => $product->getId()));
        $collection->getSelect()->order('rand()');
        return $collection;
    }
    /**
     * Get slider products including additional products
     *
     * @return array
     */
    public function getSliderProductsCollection()
    {
        $collection = [];
        $type = $this->_slider->getType();
        $featuredProducts = $this->getSliderProducts("featured");
        if(count($featuredProducts)>0){
            $collection['featured'] = $featuredProducts;
        }
        if($type !== "featured"){
            if($this->_productsNumber > 0){
                $sliderProducts = $this->getSliderProducts($type);
                if(count($sliderProducts)>0){
                    $collection['products'] = $sliderProducts;
                }
            }
        }
        return $collection;
    }
    /**
     * Create import services form select element
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        if($this->_coreRegistry->registry('enable_swatches')){
            $block = $this->getLayout()->createBlock('Magento\Framework\View\Element\RendererList',
                                                     'details.renderers'.$this->getSliderId() );
            $this->getLayout()->setChild($this->getNameInLayout(),
                                         $block->getnameInLayout(),
                                         'details.renderers'.$this->getSliderId() );
            $block = $this->getLayout()->createBlock('Magento\Swatches\Block\Product\Renderer\Listing\Configurable',
                                                     'configurable'.$this->getSliderId(),
                                                     ['data' =>
                                                        ['slider_id' => $this->getSliderId()]
                                                     ])
                                        ->setTemplate('Atopt_Slider::swatches/renderer.phtml');
            $this->getLayout()->setChild('details.renderers'.$this->getSliderId(),
                                         $block->getNameInLayout(),
                                         'configurable'.$this->getSliderId() );
            $block = $this->getLayout()->createBlock('Magento\Framework\View\Element\Template',
                                                     'default'.$this->getSliderId());
            $this->getLayout()->setChild('details.renderers'.$this->getSliderId(),
                                         $block->getNameInLayout(),
                                         'default'.$this->getSliderId());
        }
        return parent::_prepareLayout();
    }
    /**
     * Get start of day date
     *
     * @return string
     */
    public function getStartOfDayDate()
    {
        return $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
    }
    /**
     * Get end of day date
     *
     * @return string
     */
    public function getEndOfDayDate()
    {
        return $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
    }
    /**
     * Set slider model
     *
     * @param \Atopt\Slider\Model\ProductSlider $slider
     *
     * @return this
     */
    public function setSlider($slider)
    {
        $this->_slider = $slider;
        return $this;
    }
    /**
     * Get slider id
     *
     * @return int
     */
    public function getSliderId()
    {
        if(!$this->_slider){
            return $this->_coreRegistry->registry('slider_id');
        }
        return $this->_slider->getId();
    }
    /**
     * Get slider
     *
     * @return \Atopt\Slider\Model\ProductSlider
     */
    public function getSlider()
    {
        return $this->_slider;
    }
    /**
     * @param int
     *
     * @return this
     */
    public function setSliderId($sliderId)
    {
        $this->_sliderId = $sliderId;
        $slider = $this->_sliderFactory->create()->load($sliderId);
        if($slider->getId()){
            $this->setSlider($slider);
            $this->setTemplate($this->_template);
        }
        return $this;
    }
    /**
     * @return string
     */
    public function getSliderDisplayId()
    {
        return $this->_dateTime->timestamp().$this->getSliderId();
    }
    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    /**
     * @return int
     */
    public function getProductsCount()
    {
        $items = self::MAX_PRODUCTS_COUNT;
        /**
        * Total number of products in slider must be equal with getProductsNumber
        * If not configured in slider settings then default MAX_PRODUCTS_COUNT is used
        * Additional featured products plus base slider type products equals total slider products
        */
        if(!$this->_productsNumber){
            if($this->_slider->getProductsNumber() > 0 && $this->_slider->getProductsNumber() <= self::MAX_PRODUCTS_COUNT ){
                $items = $this->_slider->getProductsNumber();
            }
        } else {
            $items = $this->_productsNumber;
        }
        return $items;
    }
    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
    /**
     * Retrieve product details html
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return mixed
     */
    public function getRevoProductDetailsHtml(\Magento\Catalog\Model\Product $product)
    {
        $renderer = $this->getDetailsRenderer($product->getTypeId().$this->getSliderId());
        if ($renderer) {
            $renderer->setProduct($product);
            return $renderer->toHtml();
        }
        return '';
    }
    /**
     * @param null $type
     * @return bool|\Magento\Framework\View\Element\AbstractBlock
     */
    public function getDetailsRenderer($type = null)
    {
        if ($type === null) {
            $type = 'default';
        }
        $rendererList = $this->getDetailsRendererList();
        if ($rendererList) {
            return $rendererList->getRenderer($type, 'default'.$this->getSliderId());
        }
        return null;
    }
    /**
     * @return \Magento\Framework\View\Element\RendererList
     */
    protected function getDetailsRendererList()
    {
        return $this->getDetailsRendererListName() ? $this->getLayout()->getBlock(
            $this->getDetailsRendererListName()
        ) : $this->getChildBlock('details.renderers'.$this->getSliderId());
    }
}
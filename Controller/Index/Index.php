<?php

namespace Growthrocket\Tradegecko\Controller\Index;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends \Magento\Framework\App\Action\Action
{



    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        \Growthrocket\Tradegecko\Helper\Data $helper
    ) {
    	$this->_helper = $helper;
        parent::__construct($context);
    }



    public function execute()
    {

        $this->_helper->check();
    }
}
<?php
namespace Growthrocket\Tradegecko\Observer;

class Saveproduct implements \Magento\Framework\Event\ObserverInterface
{



   public function __construct(
        \Growthrocket\Tradegecko\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }



  public function execute(\Magento\Framework\Event\Observer $observer)
  {
			$data = $observer->getEvent()->getProduct();
			$result = $this->_helper->generateproduct($data);
     		return $this;
  }
}
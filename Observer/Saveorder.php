<?php
namespace Growthrocket\Tradegecko\Observer;

class Saveorder implements \Magento\Framework\Event\ObserverInterface
{
  public function execute(\Magento\Framework\Event\Observer $observer)
  {
     //$order= $observer->getData('order');
	 //$order->doSomething();

     return $this;
  }
}
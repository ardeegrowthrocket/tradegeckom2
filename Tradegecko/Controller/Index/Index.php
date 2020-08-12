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
      if(!empty($_GET['listtest'])){
        var_dump($this->_helper->check());
      }
      if(!empty($_GET['installhook'])){
      $webhooks = $this->_helper->_gethooks();

      foreach($webhooks as $d){
        $data = array();
        $data['webhook']['address'] = "https://staging.anotoys.com/tradegecko";
        $data['webhook']['event'] = $d;
        $wb = $this->_helper->createwebhook($data);

        var_dump($wb);

      }
      }




        $data = file_get_contents("php://input");
        $events = json_decode($data, true);

      if(count($events))
      {

        $this->_helper->log($events);

          if($events['event']=='variant.stock_level_update'){
             $data = $this->_helper->fetchhook($events['resource_url']);
             $this->_helper->log($data->variant->available_stock."===".$data->variant->id);

            $collection = $this->_helper->loadCollect();
            $collection->addAttributeToSelect('*');
            $collection->addAttributeToFilter('tg_vid',$data->variant->id);
            $collection->setPageSize(1); // fetching only 3 products

             foreach($collection as $proddata){
               $this->_helper->log($proddata->getId());
                $product = $this->_helper->loadProduct($proddata->getId());
                $item = ['qty' => $data->variant->available_stock]; // For example
                $product->setStockData(['qty' => $item['qty'], 'is_in_stock' => $item['qty'] > 0]);
                $product->save();

            }           


          }


      }

       if(!empty($_GET['checkurl'])){
          
          $data = $this->_helper->fetchhook($_GET['resource_url']);
          var_dump($data->variant->available_stock);
          var_dump($data->variant->id);
          $this->_helper->log($data->variant->available_stock);
       }





      
    }
}
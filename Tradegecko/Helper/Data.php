<?php
namespace Growthrocket\Tradegecko\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Zend\Log\Writer\Stream;
use Zend\Log\Logger;
class Data extends AbstractHelper
{


   protected $stockRegistry;
   protected $scopeConfig;
   protected $productRepository;
   protected $order;   
   protected $logger;


   public function __construct(
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Sales\Model\OrderRepository $order,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    ) {
        $this->stockRegistry = $stockRegistry;  
        $this->scopeConfig = $scopeConfig;
        $this->productRepository = $productRepository;
        $this->order = $order;
        $this->collectionFactory = $collectionFactory;
        //$this->logger = $logger;
        //parent::__construct();
    }

    public function loadCollect(){

        return $this->collectionFactory->create();
    }
    public function log($logs){
        //$this->_logger->debug($_REQUEST);
       $writer = new Stream(BP . '/var/log/tg.log');
       $logger = new Logger();
       $logger->addWriter($writer);
       $logger->info($logs);
    }
    public function test(){
        return 1;
    }

    public function loadOrder($id){
        return $this->order->get($id); 
    }


    public function loadProduct($id){
        return $this->productRepository->getById($id);
    }



    public function getStock($data){


        $productStock = $this->stockRegistry->getStockItem($data->getId());

        return $productStock->getData();
    }


    public function getRequest()
    {
        //return 1;
        return $this->scopeConfig->getValue(
            'growthrocket/general/token_id','store',0
        );

    }
    


    public function fetchhook($url){



        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));

        curl_close($ch);

         return $response;


    }



    public function verify($page,$id){



        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/'.$page.'/'.$id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));

        curl_close($ch);

        if(!empty($response->message)){
         return 0;
        }else{
            return 1;
        }




    }


    public function check(){



        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/'.$_GET['page'].'/'.$_GET['test']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));
        //var_dump($ch);
        echo "<pre>";   
        var_dump($response);
        if(!empty($response->message)){
         var_dump($response->message);
        }
        curl_close($ch);


        return $response;
    }




    public function createwebhook($data){



        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/webhooks');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));
        //var_dump($ch);
        //var_dump($response);
        curl_close($ch);


        return $response;
    }





    public function createproduct($data){



        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/products');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));
        //var_dump($ch);
        //var_dump($response);
        curl_close($ch);


        return $response;
    }





    public function createproductedit($data,$id){

    
        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/products/'.$id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));



        //var_dump($ch);
        //var_dump($response);
        curl_close($ch);




        return $response;
    }


    public function createorder($data){


        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/orders');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));
        //var_dump($ch);
        //var_dump($response);
        curl_close($ch);


        return $response;
    }


    public function createcompany($data){


        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/companies');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));
        //var_dump($ch);
        //var_dump($response);
        curl_close($ch);


        return $response;
    }


    public function createaddress($data){


        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/addresses');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));
        //var_dump($ch);
        //var_dump($response);
        curl_close($ch);


        return $response;
    }


    public function _gethooks(){
    $d = array();
    $d[] = "invoice.create";
    $d[] = "order.create";
    $d[] = "invoice.destroy";
    $d[] = "order.finalized";
    $d[] = "order.fulfilled";
    $d[] = "payment.create";
    $d[] = "product.create";
    $d[] = "product.destroy";
    $d[] = "variant.stock_level_update";
    $d[] = "stock_adjustment.create";
    $d[] = "purchase_order.create";
    $d[] = "purchase_order.received";

    return $d;
    }



/*
invoice.create
order.create
invoice.destroy
order.finalized
order.fulfilled
payment.create
product.create
product.destroy
variant.stock_level_update
stock_adjustment.create
purchase_order.create
purchase_order.received
*/

    public function createvariant($data){



        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/variants');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch));
        //var_dump($ch);
        //var_dump($response);
        curl_close($ch);


        return $response;
    }

    public function generateproduct($data){


            $vid = $vidcheck = $data->getTgVid();
            $pid = $pidcheck = $data->getTgPid();



if(empty($this->verify('variants',$vidcheck)))
{
    $vidcheck = 0;
}
if(empty($this->verify('products',$pidcheck)))
{
    $pidcheck = 0;
}

if(empty($vidcheck) || empty($pidcheck)){


            $manufacturer = $data->getAttributeText('manufacturer');
            $type = $data->getAttributeText('anotoys_type');
            $sku = $data->getSku();
            $price = $data->getPrice();
            $special_price = $data->getSpecialPrice();
            $name = $data->getName();
            $stock =  $this->getStock($data);
            $description = $data->getDescription();
            $status = 'disabled';
            if($data->getStatus()){
                $status = 'active';
            }

            $product = array();

            $product['product']['name'] = $name;
            $product['product']['description'] = $description;
            $product['product']['brand'] = $manufacturer;
            $product['product']['status'] = $status;
            $product['product']['sku'] = $data->getSku();
            $product['product']['id'] = $data->getId();
            $product['stock'] = $stock;
            $product['api'] = $this->getRequest();

            $product['product']['product_type'] = $type;
             $product['product']['image_url'] = "https://www.anotoys.com/pub/media/catalog/category/sscbase-thejokerpf.jpg";

            $create = $this->createproduct($product);
            $pid = $create->product->id;


            $variant = array();
            $variant['variant']['product_id'] = $pid;
            $variant['variant']['initial_cost_price'] = $data->getPrice();
            $variant['variant']['buy_price'] = $data->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
            $variant['variant']['initial_stock_level'] = $stock['qty'];
            $variant['variant']['retail_price'] = $data->getPrice();
            $variant['variant']['name'] = $name;
            $variant['variant']['sku'] = $data->getSku();
            $vid = 0;

            $createvar = $this->createvariant($variant);

            $vid = $createvar->variant->id;


            $productdata = $this->loadProduct($data->getId());

            $productdata->setTgVid($vid);

            $productdata->setTgPid($pid);

            $productdata->save();
}
else
{




            $manufacturer = $data->getAttributeText('manufacturer');
            $type = $data->getAttributeText('anotoys_type');
            $sku = $data->getSku();
            $price = $data->getPrice();
            $special_price = $data->getSpecialPrice();
            $name = $data->getName();
            $stock =  $this->getStock($data);
            $description = $data->getDescription();
            $status = 'disabled';
            if($data->getStatus()){
                $status = 'active';
            }

            $product = array();

            $product['product']['name'] = $name;
            $product['product']['description'] = $description;
            $product['product']['brand'] = $manufacturer;
            $product['product']['status'] = $status;
            $product['product']['sku'] = $data->getSku();
            $product['product']['id'] = $data->getId();
            $product['stock'] = $stock;
            $product['api'] = $this->getRequest();

            $product['product']['product_type'] = $type;

            $product['product']['image_url'] = "https://www.anotoys.com/pub/media/catalog/category/sscbase-thejokerpf.jpg";



            $create = $this->createproductedit($product,$pidcheck);
            //$pid = $create->product->id;


            $variant = array();
            $variant['variant']['initial_cost_price'] = $data->getPrice();
            $variant['variant']['buy_price'] = $data->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
            $variant['variant']['initial_stock_level'] = $stock['qty'];
            $variant['variant']['retail_price'] = $data->getPrice();
            $variant['variant']['name'] = $name;
            $variant['variant']['sku'] = $data->getSku();


            $createvar = $this->createvariantedit($variant,$vidcheck);


            //$vid = $createvar->variant->id;


            $productdata = $data;



}
    

            //$productdata = $this->loadProduct($data->getId());

            // $data->setTgVid($vid);

            // $data->setTgPid($pid);

            // $data->save();

            //var_dump(array('vid'=>$vid,'pid'=>$pid));

    return $productdata;



    }

    public function createvariantedit($data,$id){



        $header = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$this->getRequest(),
        );


        $get_days = isset($_GET['days']) ? (int) $_GET['days'] : 1;
        unset($data['variant']['initial_cost_price']);
        //$data['variant']['available_stock'] = $data['variant']['initial_stock_level'] + 150;
        unset($data['variant']['initial_stock_level']);




        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_URL, 'https://api.tradegecko.com'.'/variants/'.$id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);


        return $response;
    }



}

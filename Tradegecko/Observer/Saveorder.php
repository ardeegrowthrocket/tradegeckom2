<?php
namespace Growthrocket\Tradegecko\Observer;

class Saveorder implements \Magento\Framework\Event\ObserverInterface
{

   public function __construct(
        \Growthrocket\Tradegecko\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }



  public function execute(\Magento\Framework\Event\Observer $observer)
  {
	 $orders = $order = $observer->getEvent()->getOrder();
	// $orderId = $order->getId();
 //  $orders = $order = $this->_helper->loadOrder(5852);

if($orders->getTgOrderId()){
	return $this;
}


 // Get Order Information
 
  $order->getEntityId();
  $order->getIncrementId();
  $order->getState();
  $order->getStatus();
  $order->getStoreId();
  $order->getGrandTotal();
  $order->getSubtotal();
  $order->getTotalQtyOrdered();
  $order->getOrderCurrencyCode();
 
  // get customer details
 
  $custLastName = $orders->getCustomerLastname();
  $custFirsrName = $orders->getCustomerFirstname();
  $billingaddress = $order->getBillingAddress();
  $shippingaddress = $order->getShippingAddress(); 
  // get Billing details  
$company = array();

$company['company']['name'] = "Order-".$order->getIncrementId();
$company['company']['company_type'] = "consumer";
$company['company']['email'] =  $orders->getCustomerEmail();
$company['company']['phone_number'] = $billingaddress->getTelephone();


$comp = $this->_helper->createcompany($company);


$company_id = $comp->company->id;
 


$billingdata = array();
$billingdata['address']['address1'] = implode(",", $billingaddress->getStreet());
$billingdata['address']['city'] = $billingaddress->getCity();
$billingdata['address']['country'] = $billingaddress->getCountryId();
$billingdata['address']['country_code'] = $billingaddress->getCountryId();
$billingdata['address']['email'] = $billingaddress->getEmail();
$billingdata['address']['first_name'] = $billingaddress->getFirstname();
$billingdata['address']['last_name'] = $billingaddress->getLastname();
$billingdata['address']['label'] = $billingaddress->getAddressType();
$billingdata['address']['phone_number'] = $billingaddress->getTelephone();
$billingdata['address']['state'] = $billingaddress->getRegion();
$billingdata['address']['zip_code'] = $billingaddress->getPostcode();
$billingdata['address']['company_id'] = $company_id;


$shippingdata = array();
$shippingdata['address']['address1'] = implode(",", $shippingaddress->getStreet());
$shippingdata['address']['city'] = $shippingaddress->getCity();
$shippingdata['address']['country'] = $shippingaddress->getCountryId();
$shippingdata['address']['country_code'] = $shippingaddress->getCountryId();
$shippingdata['address']['email'] = $shippingaddress->getEmail();
$shippingdata['address']['first_name'] = $shippingaddress->getFirstname();
$shippingdata['address']['last_name'] = $shippingaddress->getLastname();
$shippingdata['address']['label'] = $shippingaddress->getAddressType();
$shippingdata['address']['phone_number'] = $shippingaddress->getTelephone();
$shippingdata['address']['state'] = $shippingaddress->getRegion();
$shippingdata['address']['zip_code'] = $shippingaddress->getPostcode();
$shippingdata['address']['company_id'] = $company_id;


$bdata = $this->_helper->createaddress($billingdata);
$address_bid = $bdata->address->id;

$sdata = $this->_helper->createaddress($shippingdata);
$address_sid = $sdata->address->id;





  $grandTotal = $order->getGrandTotal();
  $subTotal = $order->getSubtotal();
 
  // fetch specific payment information
 
  $amount = $order->getPayment()->getAmountPaid();
  $paymentMethod = $order->getPayment()->getMethod();
  $info = $order->getPayment()->getAdditionalInformation('method_title');
 
  // Get Order Items
 
  $orderItems = $order->getAllItems();
 

 $orderdata = array();

$orderdata['order']['company_id'] = $company_id;
$orderdata['order']['shipping_address_id'] = $address_sid;
$orderdata['order']['billing_address_id'] = $address_bid;


  foreach ($orderItems as $item) {
    $item->getItemId();
    $item->getOrderId();
    $item->getStoreId();
    $item->getProductId();


    $data = $this->_helper->loadProduct($item->getProductId());
    $prod = $this->_helper->generateproduct($data);

    $itemsdata['variant_id'] = $prod->getTgVid();
    $itemsdata['quantity'] = $item->getQtyOrdered();
    $itemsdata['price'] = $item->getPrice();
    $orderdata['order']['order_line_items'][] = $itemsdata;
    // $prod->getTgVid();
 
    $item->getSku();
    $item->getName();
    $item->getQtyOrdered();
    $item->getPrice();
 }
 	$orderdata['order']['status'] = 'active';
 	
 	$tgorder = $this->_helper->createorder($orderdata);

 	$orders->setTgOrderid($tgorder->order->id);

 	$orders->save();

     return $this;
  }
}
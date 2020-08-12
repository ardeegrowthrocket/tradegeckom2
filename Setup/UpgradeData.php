<?php

namespace Growthrocket\Tradegecko\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        if (version_compare($context->getVersion(), '1.0.1') < 0){

				
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$salesSetup = $objectManager->create('Magento\Sales\Setup\SalesSetup');
				
$salesSetup->addAttribute('order', 'tg_orderid', ['type' =>'varchar']);
$salesSetup->addAttribute('invoice', 'tg_invoiceid', ['type' =>'varchar']);
$salesSetup->addAttribute('creditmemo', 'tg_creditmemoid', ['type' =>'varchar']);
				$quoteSetup = $objectManager->create('Magento\Quote\Setup\QuoteSetup');
				
				

		 }
    }
}
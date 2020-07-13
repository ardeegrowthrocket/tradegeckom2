<?php


namespace Growthrocket\Tradegecko\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Tradeckopush extends Command
{

    const NAME_ARGUMENT = "name";
    const NAME_OPTION = "option";

   public function __construct(
        \Growthrocket\Tradegecko\Helper\Data $helper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\App\State $state,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {
        $this->_helper = $helper;
        $this->state = $state;
        $this->_productCollectionFactory = $productCollectionFactory; 
        $this->stockRegistry = $stockRegistry;  
        parent::__construct();
    }
/*

189
188

catalog_product_entity_varchar


SELECT (189) as aid,(0) as sid,entity_id,(0) as value FROM `catalog_product_entity`


INSERT INTO catalog_product_entity_varchar SELECT (NULL) as vid,(188) as aid,(0) as sid,entity_id,(0) as value FROM `catalog_product_entity`


*/

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {

        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);

        $name = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);
        $output->writeln("Hello " . $name);


        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('tg_vid',0);
        $collection->setPageSize(1); // fetching only 3 products
        
        echo $collection->getSelect();  


        foreach($collection as $data){

            echo $data->getId();
            $this->_helper->generateproduct($data);


        }

        
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("growthrocket_tradegecko:tradeckopush");
        $this->setDescription("");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        parent::configure();
    }
}


	
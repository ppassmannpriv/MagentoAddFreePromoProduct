<?php

class Scriptkid_AddFreePromoproduct_Model_Observer
{
	private $_promoTriggers = false;
	private $_freeProductSkuSuffix = false;
	private $_helper = false;
	private $_breakCount = false;

	public function __construct()
	{

		$this->_helper = Mage::helper('AddFreePromoproduct');
		$this->_promoTriggers = $this->_helper->getPromoTriggers();
		$this->_freeProductSkuSuffix = $this->_helper->getFreeProductSkuSuffix();
		$this->_breakCount = $this->_helper->getBreakCount();
	}

	public function sales_quote_collect_totals_after(Varien_Event_Observer $_observer)
	{
		if ($_quote = $_observer->getEvent()->getQuote()):
			$_allQuoteItems = $_quote->getAllItems();

			foreach($_allQuoteItems as $_item):
				if($this->_helper->triggerCheckItem($_item)):
					$_freePromoproductsku = $_item->getSku().$this->_freeProductSkuSuffix;

					if($this->_helper->isInCart($_freePromoproductsku, $_allQuoteItems)):
						$this->_helper->updateFreePromoproductToCart($_freePromoproductsku, $this->_helper->getFreePromoproductQty($_item, $this->_breakCount), $_quote);
					else:
						$this->_helper->addFreePromoproductToCart($_freePromoproductsku, $_allQuoteItems, $_item, $_quote);
					endif;
				endif;
			endforeach;
		endif;
	}

	public function onSalesQuoteDelete(Varien_Event_Observer $_observer)
	{
		//delete tha free product yo.
		$_cartHelper = Mage::helper('checkout/cart');
		$_item = $_observer->getQuoteItem();
		if(in_array($_item->getSku(), $this->_promoTriggers)):
			$_deletePromoSku = $_item->getSku().$this->_freeProductSkuSuffix;

			foreach(Mage::getModel('checkout/session')->getQuote()->getAllItems() as $_item):
				if($_item->getSku() == $_deletePromoSku):

					$_cartHelper->getCart()->removeItem($_item->getItemId())->save();
				endif;
			endforeach;
		endif;


	}

	// public function onCartDelete(Varien_Event_Observer $_observer)
	// {
	//
	// }

}

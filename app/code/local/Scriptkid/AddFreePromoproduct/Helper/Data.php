<?php

class Scriptkid_AddFreePromoproduct_Helper_Data extends Mage_Core_Helper_Abstract {
  private $_promoTriggers = array('2508', '2501', '2506', '2503', '2502', '2504');
	private $_freeProductSkuSuffix = '-kostenlos';
  private $_breakCount = 20;
  private $_runOnce = array(
    '2508-kostenlos' => true,
    '2501-kostenlos' => true,
    '2506-kostenlos' => true,
    '2503-kostenlos' => true,
    '2502-kostenlos' => true,
    '2504-kostenlos' => true
  );

  //Run the _runOnce array over a constructor function and make everything nice with backend shit

  public function getPromoTriggers()
  {
    return $this->_promoTriggers;
  }

  public function getFreeProductSkuSuffix()
  {
    return $this->_freeProductSkuSuffix;
  }

  public function getBreakCount()
  {
    return $this->_breakCount;
  }

  public function triggerCheckItem($_item)
  {
    $retVal = false;

    if(in_array($_item->getSku(), $this->_promoTriggers)):
      if($this->checkQty($_item->getQty(), $this->_breakCount)):
        $retVal = true;
      endif;
    endif;

    return $retVal;
  }

  public function checkQty($_itemQty, $_breakCount)
  {
    $retVal = false;

    if($_itemQty >= $_breakCount):
      $retVal = true;
    endif;

    return $retVal;
  }

  public function getFreePromoproductQty($_item, $_breakCount)
  {
    $qty = floor($_item->getQty() / $_breakCount);

    return $qty;
  }

  public function addFreePromoproductToCart($_freePromoproductsku, $_allQuoteItems, $_item, $_quote)
  {
    $_catalog = Mage::getModel('catalog/product');
    $_freePromoproduct = $_catalog->load($_catalog->getIdBySku($_freePromoproductsku));

    if($_freePromoproduct):
      $_qty = $this->getFreePromoproductQty($_item, $this->_breakCount);

      if($this->isInCart($_freePromoproductsku, $_allQuoteItems)):
        //$this->updateFreePromoproductToCart($_freePromoproductsku, $_qty, $_quote);

      else:
        //$_quote = Mage::getSingleton('checkout/session')->getQuote();
        if($this->_runOnce[$_freePromoproductsku]):

          $this->_runOnce[$_freePromoproductsku] = false;

          $_quote->addProduct($_freePromoproduct, $_qty);
        endif;
        //$_quote->addProduct($_freePromoproduct, $_qty);
        //$_quote->save();

      endif;
    endif;
  }

  public function updateFreePromoproductToCart($_freePromoproductsku, $_qty, $_quote)
  {
    //$_quote = Mage::getSingleton('checkout/session')->getQuote();

    foreach($_quote->getAllItems() as $_item):

      if($_item->getSku() == $_freePromoproductsku):
        $_item->setQty($_qty);
        //$_item->save();
      endif;
    endforeach;
  }

  public function isInCart($_freePromoproductsku, $_allQuoteItems)
  {
    $retVal = false;
    foreach($_allQuoteItems as $_item):
      if($_item->getSku() == $_freePromoproductsku):
        $retVal = true;
      endif;
    endforeach;
    return $retVal;
  }

}

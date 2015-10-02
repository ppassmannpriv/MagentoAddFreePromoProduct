<?php
class Graphodata_AddFreePromoproduct_Model_Cart extends Mage_Checkout_Model_Cart
{
	/**
     * Remove item from cart
     *
     * @param   int $itemId
     * @return  Mage_Checkout_Model_Cart
     */
    /*public function removeItem($itemId)
    {
        Mage::dispatchEvent('checkout_cart_remove_item_before', array('cart' => $this, 'item_id' => $itemId));

		$this->getQuote()->removeItem($itemId);

		Mage::dispatchEvent('checkout_cart_remove_item_after', array('cart' => $this));

        return $this;
    }*/

}

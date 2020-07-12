<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+


class Cart
{
	var $items;  //Array of items
	var $total;  //Cart Total
	var $totalplugs; //Cart total with order total plugins factored in
	var $totalfinal; //Has order total, shipping and payment plugins factored in.
	var $tax;    // Total Tax
	var $options;	//option of selected item
	var $payment;	//Selected payment
	var $shipping;	//Selected shipping

	/**
    * Konstruktor
    * Object constructor
    */
	function Cart()
	{
		$this->init();
	}

	/**
    * This function is called to initialize (and reset) a shopping cart
    */
	function init()
	{
		//-- items are stored in an array
		//-- the rest gets an starting value of 0.

		$this->items = array();
		$this->options = array();
		$this->total = 0;
		$this->totalplugs = 0;
		$this->tax   = 0;
		$this->payment = 0;
		$this->shipping = 0;
		$_SESSION['shop_cart']['items'] = array();
		$_SESSION['shop_cart']['total'] = 0;
	}

	function ortotLoader($total){
		global $xoopsDB,$xoopsModule;

		$plugs=array();
		$q=1;
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plugins') . " WHERE type='order' ");
		while ($row = $xoopsDB->fetchArray($query))
		{
			include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/order/'.$row['dir'].'/process.php';
			$process="process_".$row['dir'];
			$ldplug=$process($total);
			if (!$ldplug) continue;
			$plugs[$q]=$ldplug;
			$total=$plugs[$q]['added'];
			$plugs[$q]['added']=BuildPrice($plugs[$q]['added']);
			$plugs[$q]['partial']=BuildPrice($plugs[$q]['partial']);
			$q++;
		}

		if ((!isset($ldplug)) || (!$ldplug)) {
			$this->totalplugs=$this->total;
			return FALSE;
		}
		$this->totalplugs=$total;
		return $plugs;
	}

	/**
Check module permission to see if order total plugins should be included in shopping cart block and cart.
*/
	function chkwhichPrice(){
		global $xoopsModuleConfig;

		if ($xoopsModuleConfig['incotplugs']){
			return $this->totalplugs;
		} else {
			return $this->total;
		}
	}

	/**
    * Add an item to the shopping cart and update the total price.
    * The quantity will not be increased if clicking multiple times.
    */
	function addItem($pid, $qty, $opt_id)
	{
	global $xoopsUser,$CART;

		$this->items[$pid] = 0;
		$this->items[$pid] += $qty;
		$this->options[$pid] = $opt_id;
		$this->total=$this->productTotal();
		$totalplugs=$this->ortotLoader($this->total);

		$_SESSION['shop_cart']['items'] = $this->countItems();
		$_SESSION['shop_cart']['total'] = $this->chkwhichPrice();		
		
		}

	/**
    * This function will remove a given product from the cart.
    */
	function deleteItem(&$productid)
	{
		if (isset($productid))
		{
		unset($this->items[$productid]);
		unset ($this->options[$productid]);
		$this->cartTotal();
		$this->ortotLoader($this->total);
		$_SESSION['shop_cart']['items'] = $this->countItems();
		$_SESSION['shop_cart']['total'] = $this->chkwhichPrice();
		}
	}

	/**
    * Set the quantity of a product in the cart to a specified value.
    */
	function changeQuantity($pid, $qty)
	{
		$this->items[$pid] = (int) $qty;
		$this->cartTotal();
		$this->ortotLoader($this->total);
		$_SESSION['shop_cart']['items'] = $this->countItems();
		$_SESSION['shop_cart']['total'] = $this->chkwhichPrice();
	}

	/**
    * Returns the number of individual items in the shopping cart
    * (note, this takes into account the quantities of the items as well).
    */
	function countItems()
	{
		$count = 0;
		foreach ($this->items as $productid => $qty)
		{
			$count += $qty;
		}
		return $count;
	}

	/**
    * Private function.
    * This function will clean up the cart,
    * removing items with invalid product id's (non-numeric ones) and
    * products with quantities less than 1.
    */
	function _cleanup()
	{
		foreach ($this->items as $productid => $qty)
		{
			if ($qty < 1)
			{
				unset($this->items[$productid]);
			}
		}
	}

	/**
    * Returns a comma delimited list of all the products in the cart.
    * This will be used for queries, e.g.
    * SELECT id, price FROM products WHERE id IN ....
    * Return value is the list.
    */
	function get_productid_list()
	{
		$productid_list = "";

		foreach ($this->items as $productid => $qty)
		{
			$productid_list .= ",'" . $productid . "'";
		}

		//-- Need to strip off the leading comma.
		return substr($productid_list, 1);
	}

	/**
    * Calculate the total of the shopping cart.
    */
	function cartTotal()
	{
		global $xoopsDB;
		$this->total = 0;
		$this->tax   = 0;

		//-- We will do some cleanup and remove invalid items from the cart.
		$this->_cleanup();

		//-- If the cart is empty just return.
		$in_clause = $this->get_productid_list();
		if (empty($in_clause))
		{
			return;
		}

		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products') . " WHERE p_id IN ($in_clause) ");
		while ($row = $xoopsDB->fetchArray($query))
		{
			$pid=$row['p_id'];
			if (($row['p_has_special']==1) && ($row['p_sp_price']!=0)){
				$price=$row['p_sp_price'];
			} else {
				$price=$row['p_price'];
			}


			//-- Calculation of the total price.
			$this->total += $this->items[$pid] * $price;

			//-- Calculation of the VAT.
			//$this->tax = $product->p_tax;
			//$this->tax = $this->items[$pid] * $row['p_price'];

		}
	}


	function productTotal(){
		global $xoopsDB;
		$this->_cleanup();
		$prod_total=0;

		//-- If the cart is empty just return.
		$in_clause = $this->get_productid_list();
		if (empty($in_clause))
		{
			return;
		}


		$query = $xoopsDB->query(' SELECT p_id, p_price, p_sp_price, p_has_special, p_tax FROM ' . $xoopsDB->prefix('kshop_products')." WHERE p_id IN ($in_clause) ");
		while ($row = $xoopsDB->fetchArray($query)){

			$sel=$row['p_id'];
			$qty=$this->items[$sel];

			if (($row['p_has_special']=="1") && ($row['p_sp_price'] != "")){
				$prod_total += $row['p_sp_price']*$qty;
			}else{
				$prod_total += $row['p_price']*$qty;
			}
		}
		return $prod_total;
	}

	//Load selected payment.
	function loadPayment($total){
	global $xoopsDB,$xoopsModule;
		$pay=$this->payment;
		include XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/payment/'.$pay.'/process.php';
		$process="process_".$pay;
		$paymethod=$process($total);
		return $paymethod;
	}

	//Load selected shipping.
	function loadShipping($total){
	global $xoopsDB,$xoopsModule;
		$ship=$this->shipping;
		include XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/shipping/'.$ship.'/process.php';
		$process="process_".$ship;
		$shipmethod=$process($total);
		return $shipmethod;
	}



	//Place selected payment in session
	function selectPayment($pay){
		$this->payment=$pay;
	}

	//Place selected shipping in session
	function selectShipping($ship){
		$this->shipping=$ship;
	}

}

?>
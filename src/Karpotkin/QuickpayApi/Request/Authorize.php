<?php namespace Karpotkin\QuickpayApi\Request;

/**
 * Authorize transaction request
 *
 * NB! authorize only allowed from a PCI certificed environment
 *
 * @author Tim Warberg <tlw@interface.dk>, interFace ApS
 *
 * Copyright (C) 2012 Tim Warberg <tlw@interface.dk>, interFace ApS
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */
class Authorize extends Request
{
	/**
	 * Setup API authorize request
	 *
	 * @param integer $quickpayID	QuickPay ID (Found in manager)
	 * @param string  $md5check		QuickPay MD5Check (Found in manager)
	 * @param boolean $apiUrl		(optional) alternate API url
	 * @param boolean $verifySSL 	(optional) disable SSL certificate verification
	 */
    public function __construct($quickpayID, $md5check, $apiUrl = false, $verifySSL = true) {
        parent::__construct($quickpayID, $md5check, $apiUrl, $verifySSL);
        $this->_set('msgtype','authorize');
    }

    /**
     * An unique order number. Must be between 4 and 20 characters long
     * @param string $ordernumber
     * @return Authorize
     */
    public function setOrderNumber($ordernumber) {
        $this->_set('ordernumber',$ordernumber);
        return $this;
    }

    /**
     * Amount in currency's smallest unit. fx. 1.23 DKK is written as 123
     * @param integer $amount
     * @return Authorize
     */
    public function setAmount($amount) {
        $this->_set('amount',$amount);
        return $this;
    }

    /**
     * 3 letter transaction currency (ISO 4217)
     * @param string $currency
     * @return Authorize
     */
    public function setCurrency($currency) {
        $this->_set('currency',$currency);
        return $this;
    }

    /**
     * Enable auto capture.
     * @param boolean $autocapture True for autocapture
     * @return Authorize
     */
    public function setAutoCapture($autocapture) {
        $this->_set('autocapture',$autocapture ? '1' : '0');
        return $this;
    }

    /**
     * (Credit)card number
     * @param integer $cardnumber
     * @return Authorize
     */
    public function setCardnumber($cardnumber) {
        $this->_set('cardnumber',$cardnumber);
        return $this;
    }

    /**
     * (Credit)card expiration date. Format: MMYY
     * @param string $expdate
     * @return Authorize
     */
    public function setExpirationDate($expdate) {
        $this->_set('expirationdate', $expdate);
        return $this;
    }

    /**
     * (Credit)card 3 digit cvd/cvv
     * @param integer $cvd
     * @return Authorize
     */
    public function setCVD($cvd) {
        $this->_set('cvd',$cvd);
        return $this;
    }

    /**
     * Restrict to specific card type(s).
     * @param string $cardtypelock Comma separated list of locknames
     * @return Authorize
     */
    public function setCardtypeLock($cardtypelock) {
        $this->_set('cardtypelock',$cardtypelock);
        return $this;
    }

    /**
     * Allow partial/split capture
     * @param boolean $split True for split payment
     * @return Authorize
     */
    public function setSplitpayment($split) {
        $this->_set('splitpayment',$split ? '1' : '0');
        return $this;
    }

    /**
     * Set channel. Used to switch between mobile (Mobilpenge) and credit card payments
     * @param string $channel creditcard/mobile
     * @return Authorize
     */
    public function setChannel($channel) {
    	$this->_set('channel', $channel);
    	return $this;
    }

    /**
     * Mobile number. Only relevant when channel=mobile
     * @param integer $number
     * @return Authorize
     */
    public function setMobileNumber($number) {
    	$this->_set('mobilenumber', $number);
    	return $this;
    }

    /**
     * Description of purchase to be included in purchase confirmation SMS. Keep it at short as possible
     * @param string $message Short purchase description
     * @return Authorize
     */
    public function setSmsMessage($message) {
    	$this->_set('smsmessage', $message);
    	return $this;
    }

    /**
     * Enable card hash in response. Card hash is a unique but PCI safe hash ID based on card number.
     * @param boolean $hash Enable hash?
     * @return Authorize
     */
    public function setCardHash($hash) {
    	$this->_set('cardhash', $hash ? '1' : '0');
        return $this;
    }

    /**
     * Set the acquirer to which the request is sent to
     * @param string $acquirers Comma separated list with highest priority acquirer first
     * @return $this
     */
    public function setAcquirers($acquirers) {
        $this->_set('acquirers', $acquirers);
        return $this;
    }
}

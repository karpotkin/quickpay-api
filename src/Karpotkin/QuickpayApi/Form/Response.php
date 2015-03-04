<?php namespace Karpotkin\QuickpayApi\Form;

/**
 * QuickPay Form Response
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
class Response
{
    /**
     * Fields used for md5 check
     * @var array
     */
    protected static $md5checkFields = array(
		'msgtype',
		'ordernumber',
		'amount',
		'currency',
		'time',
		'state',
		'qpstat',
		'qpstatmsg',
		'chstat',
		'chstatmsg',
		'merchant',
		'merchantemail',
		'transaction',
		'cardtype',
		'cardnumber',
		'cardhash',
		'cardexpire',
		'acquirer',
		'splitpayment',
		'fraudprobability',
		'fraudremarks',
		'fraudreport',
		'fee'
    );

    /**
     * The response array
     * @var array
     */
    protected $response;

    /**
     * The custom variables
     * @var array
     */
    protected $custom = array();

    /**
     * Make a response object from the callback
     * @param array $post
     */
    public function __construct($post) {
        $this->response = $this->parsePost($post);
    }

    /**
     * Get a value from the response
     * @param string $key
     * @return mixed
     */
    public function get($key) {
        if (array_key_exists($key, $this->response)) {
            return $this->response[$key];
        }

        if (array_key_exists($key, $this->custom)) {
            return $this->custom[$key];
        }

        return null;
    }

    /**
     * Get the order number
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->get('ordernumber');
    }

    /**
     * Get the transaction number
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->get('transaction');
    }

    /**
     * Get the expiration date from the used credit card
     * @return string
     */
    public function getCardExpire()
    {
        return $this->get('cardexpire');
    }

    /**
     * Get the card number from the used credit card
     * @return string
     */
    public function getCardNumber()
    {
        return $this->get('cardnumber');
    }

    /**
     * Get the used credit card type
     * @return string
     */
    public function getCardType()
    {
        return $this->get('cardtype');
    }

    /**
     * Check if the request was successful
     * @return bool
     */
    public function isSuccess() {
        return $this->get('qpstat') == '000';
    }

    /**
     * Check if the request was valid
     * @param string $md5check
     * @return bool
     */
    public function isValid($md5check) {
        $md5string = '';

        foreach(static::$md5checkFields as $key) {
            if (array_key_exists($key, $this->response)) {
                $md5string .= $this->response[$key];
            }
        }

        return strcmp($this->response['md5check'],md5($md5string . $md5check)) === 0;
    }

    /**
     * Parse the POST data
     * @param array $post
     * @return array
     */
    protected function parsePost($post) {

        $response = array();

        // Parse the md5 checked fields
        foreach (array_merge(static::$md5checkFields, array('md5check')) as $name) {
            $response[$name] = isset($post[$name]) ? $post[$name] : null;
        }

        // Parse custom variables
        foreach ($post as $name => $value) {
            if (strpos($name, 'CUSTOM_') === 0 and strlen($name) > 7) {
                $this->custom[substr($name, 7)] = $value;
            }
        }

        return $response;
    }
}
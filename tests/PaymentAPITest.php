<?php

require_once 'config.php.dist';

use Karpotkin\QuickpayApi\Request\Authorize;
use Karpotkin\QuickpayApi\Request\Cancel;
use Karpotkin\QuickpayApi\Request\Capture;
use Karpotkin\QuickpayApi\Request\Recurring;
use Karpotkin\QuickpayApi\Request\Subscribe;
use Karpotkin\QuickpayApi\Request\Refund;

class PaymentAPITest extends PHPUnit_Framework_TestCase
{
    protected function stdAuthorize() {
        $auth = new Authorize(QuickPayID,MD5Check,APIURL,VERIFYSSL);
        $response = $auth->setAPIKey(APIKEY)
            ->setOrderNumber('A'. $this->createOrdernumber())
            ->setAmount(234)
            ->setCurrency('DKK')
            ->setCardnumber(CREDITCARD)
            ->setExpirationDate(EXPIRE)
            ->setCVD(CVD)
            ->setCardtypeLock('dankort')
            ->setTestmode(true)
            ->send();

        $this->assertEquals(true, $response->isValid(), 'Authorize not valid');
        $this->assertEquals(true, $response->isSuccess(), 'Authorize not successful');
        return $response;
    }

    protected function stdSubscribe() {
        $auth = new Subscribe(QuickPayID,MD5Check,APIURL,VERIFYSSL);
        $response = $auth->setAPIKey(APIKEY)
            ->setOrderNumber('S'.$this->createOrdernumber())
            ->setCurrency('DKK')
            ->setCardnumber(4571999999999999)
            ->setExpirationDate(1212)
            ->setCVD(123)
            ->setCardtypeLock('dankort')
            ->setDescription('interFace API Client test')
            ->setTestmode(true)
            ->send();

        $this->assertEquals(true, $response->isValid(), 'Subscribe not valid');
        $this->assertEquals(true,$response->isSuccess(), 'Subscribe not successful');
        return $response;
    }

    protected function stdCapture() {
        $auth = $this->stdAuthorize();
        $capture = new Capture(QuickPayID,MD5Check,APIURL,VERIFYSSL);
        $response = $capture->setAPIKey(APIKEY)
            ->setTransaction($auth->get('transaction'))
            ->setAmount(234)
            ->send();

        $this->assertEquals(true, $response->isValid(), 'Capture not valid');
        $this->assertEquals(true, $response->isSuccess(), 'Capture not successful');
        return $response;
    }

    public function testAuthorize() {
    	$this->stdAuthorize();
    }

    public function testCardHashAuthorize() {
    	$auth = new Authorize(QuickPayID,MD5Check,APIURL,VERIFYSSL);
        $response = $auth->setAPIKey(APIKEY)
            ->setOrderNumber('A'. $this->createOrdernumber())
            ->setAmount(234)
            ->setCurrency('DKK')
            ->setCardnumber(CREDITCARD)
            ->setExpirationDate(EXPIRE)
            ->setCVD(CVD)
            ->setCardtypeLock('dankort')
            ->setCardHash(true)
            ->setTestmode(true)
            ->send();

        $this->assertEquals(true, $response->isValid(), 'Authorize not valid');
        $this->assertEquals(true, $response->isSuccess(), 'Authorize not successful');
    }

    public function testCapture() {
        $this->stdCapture();
    }

    public function testCancel() {
        $auth = $this->stdAuthorize();
        $cancel = new Cancel(QuickPayID,MD5Check,APIURL,VERIFYSSL);
        $response = $cancel->setAPIKey(APIKEY)
            ->setTransaction($auth->get('transaction'))
            ->send();

        $this->assertEquals(true, $response->isValid(), 'Cancel not valid');
        $this->assertEquals(true, $response->isSuccess(), 'Cancel not successful');
    }

    public function testRefund() {
        $capture = $this->stdCapture();
        $refund = new Refund(QuickPayID,MD5Check,APIURL,VERIFYSSL);
        $response = $refund->setAPIKey(APIKEY)
            ->setTransaction($capture->get('transaction'))
            ->setAmount(234)
            ->send();

        $this->assertEquals(true, $response->isValid(), 'Response not valid');
        $this->assertEquals(true, $response->isSuccess(), 'Response not success');
    }

    public function testRecurring() {
        $subscribe = $this->stdSubscribe();
        $recurring = new Recurring(QuickPayID,MD5Check,APIURL,VERIFYSSL);
        $response = $recurring->setAPIKey(APIKEY)
            ->setTransaction($subscribe->get('transaction'))
            ->setOrderNumber('R'.$this->createOrdernumber())
            ->setAmount(234)
            ->setCurrency('DKK')
            ->setAutoCapture(true)
            ->send();

        $this->assertEquals(true, $response->isValid(), 'Recurring not valid');
        $this->assertEquals(true, $response->isSuccess(), 'Recurring not successful');
    }

    public function testInvalidRequest() {
    	$auth = new Authorize(QuickPayID,MD5Check,APIURL,VERIFYSSL);
        $response = $auth->setAPIKey(APIKEY)
            ->setOrderNumber('1')
            ->setAmount(234)
            ->setCurrency('DKK')
            ->setCardnumber(CREDITCARD)
            ->setExpirationDate(EXPIRE)
            ->setCVD(CVD)
            ->setCardtypeLock('dankort')
            ->setTestmode(true)
            ->send();

        $this->assertTrue($response->isValid(), 'Authorize response not valid');
        $this->assertFalse($response->isSuccess(), 'Authorize successful');
    }

    protected function createOrdernumber()
    {
        list($usec, $sec) = explode(" ", microtime());
        return preg_replace('/\./', '', ((float)$usec + (float)$sec));
    }
}

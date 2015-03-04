<?php namespace Karpotkin\QuickpayApi\Form;

class Authorize extends Builder {

    public function __construct($quickpayID, $md5check) {
        parent::__construct($quickpayID, $md5check);
        $this->setField('msgtype', 'authorize');
    }
}
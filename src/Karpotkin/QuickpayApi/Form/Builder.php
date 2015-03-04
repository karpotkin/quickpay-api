<?php namespace Karpotkin\QuickpayApi\Form;

abstract class Builder
{
    const ACTION = 'https://secure.quickpay.dk/form/';

    protected $testmode = 0;

    protected $merchant;

    protected $protocol = 7;

    protected $md5Check;

    public $fields = [];

    public $customFields = [];

	protected static $md5checkFields = array(
        'protocol',
        'msgtype',
        'merchant',
        'language',
        'ordernumber',
        'amount',
        'currency',
        'continueurl',
        'cancelurl',
        'callbackurl',
        'autocapture',
        'autofee',
        'cardtypelock',
        'description',
        'group',
        'testmode',
        'splitpayment',
        'forcemobile',
        'deadline',
        'cardhash'
    );

    public function __construct($quickpayID, $md5check) {
        $this->merchant = $quickpayID;
        $this->md5Check = $md5check;
    }

    public function getAction()
    {
        return static::ACTION;
    }

    public function setField($name, $value)
    {
        $this->fields[$name] = $value;
    }

    public function setFields($inputFields)
    {
        foreach ($inputFields as $key => $value)
        {
            $this->fields[$key] = $value;
        }
    }

    protected function prepareFields()
    {
        $reservedFields = array('protocol', 'merchant', 'testmode');

        foreach ($reservedFields as $field) {
            $this->fields[$field] = $this->$field;
        }

        // Sort for MD5 calculation
        $sorted = [];

        foreach (static::$md5checkFields as $field) {
            if (isset($this->fields[$field])) {
                $sorted[$field] = $this->fields[$field];
            }
        }

        $sorted['md5check'] = md5(implode("", $sorted) . $this->md5Check);

        $this->fields = $sorted;
    }

    public function getFields($xhtml = false)
    {
        $this->prepareFields();

        $html = '';
        $html_end = ($xhtml) ? ' />' : '>';

        foreach(array_merge($this->fields, $this->customFields) as $key => $value)
        {
            $html .= '<input type="hidden" name="'.$key.'" value="'.$value.'"'.$html_end;
        }

        return $html;
    }

    public function setCustom($name, $value)
    {
        $this->customFields['CUSTOM_'.$name] = $value;
    }
}

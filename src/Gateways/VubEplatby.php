<?php

namespace Crm\VubEplatbyModule\Gateways;

use Crm\PaymentsModule\Gateways\GatewayAbstract;
use Omnipay\Eplatby\Gateway;
use Omnipay\Omnipay;

class VubEplatby extends GatewayAbstract
{
    public const GATEWAY_CODE = 'vub_eplatby';

    /** @var Gateway */
    protected $gateway;

    protected function initialize()
    {
        $this->gateway = Omnipay::create('Eplatby');

        $this->gateway->setSharedSecret($this->applicationConfig->get('vub_eplatby_sharedsecret'));
        $this->gateway->setMid($this->applicationConfig->get('vub_eplatby_mid'));
        $this->gateway->setTestMode(!($this->applicationConfig->get('vub_eplatby_mode') === 'live'));
        $this->gateway->setTestHost($this->testHost);
    }

    public function begin($payment)
    {
        $this->initialize();

        $request = [
            'amount' => $payment->amount,
            'vs' => $payment->variable_symbol,
            'cs' => $this->applicationConfig->get('vub_eplatby_constant_symbol'),
            'rurl' => $this->generateReturnUrl($payment),
        ];

        $referenceEmail = $this->applicationConfig->get('vub_eplatby_rem');
        if ($referenceEmail) {
            $request['rem'] = $referenceEmail;
        }

        $this->response = $this->gateway->purchase($request)->send();
    }

    public function complete($payment): ?bool
    {
        $this->initialize();
        $this->response = $this->gateway->completePurchase()->send();

        return $this->response->isSuccessful();
    }
}

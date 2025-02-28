<?php
namespace Ollyo\Task\Controllers;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class Controller
{
    public static function checkout() {
        $paypalClientId = 'AXfSjg7iEcUh5amMdI-uiNscXtcrvecgC7ynbySqeQ7T5tptGjshWyGhzujzrQbIuAmNZMsIz_h6QVu5';
        $paypalClientSecret = 'ECC2iLItOedR6sxM2bjVihr4TWXKkbz2B2l52-P02w_SQ1eeUMdqkYdmsGdSXiq5w6LIkcv_YS8aWaSB';

        $apiContext = new ApiContext(
            new OAuthTokenCredential($paypalClientId, $paypalClientSecret)
        );

        $apiContext->setConfig([
            'mode' => 'sandbox',
            'http.ConnectionTimeOut' => 30,
        ]);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // TODO: Loop through the actual products array and create Items
        $item = new Item();
        $item->setName('Test Product from Ollyo')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($_POST['payableAmount']);

        $itemList = new ItemList();
        $itemList->addItem($item);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($_POST['payableAmount']);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Ollyo Test Product');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl('http://localhost:8000/payment/success')
            ->setCancelUrl('http://localhost:8000/payment/cancel');

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $approvalUrl = $payment->getApprovalLink();

        header('Location: ' . $approvalUrl);
        exit;
    }
}
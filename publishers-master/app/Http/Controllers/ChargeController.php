<?php
namespace App\Http\Controllers;
        //merchant credentials
        const MERCHANT_LOGIN_ID = "5KP3u95bQpv";
        const MERCHANT_TRANSACTION_KEY = "346HZ32z3fP4hTG2";

        const RESPONSE_OK = "Ok";

        //Recurring Billing
        const SUBSCRIPTION_ID_GET = "2930242";
        //Transaction Reporting
        const TRANS_ID = "2238968786";
        const SAMPLE_AMOUNT = "2.23";
        define("AUTHORIZENET_LOG_FILE", "phplog");
use Illuminate\Http\Request;
  use net\authorize\api\contract\v1 as AnetAPI;
  use net\authorize\api\controller as AnetController;
class ChargeController extends Controller
{
    public function index()
    {
    $amount = SAMPLE_AMOUNT;
    // Common setup for API credentials
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(MERCHANT_TRANSACTION_KEY);
    $refId = 'ref' . time();
    // Create the payment data for a credit card
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber("4111111111111111");
    $creditCard->setExpirationDate("1226");
    $creditCard->setCardCode("123");
    $paymentOne = new AnetAPI\PaymentType();
    $paymentOne->setCreditCard($creditCard);
    $order = new AnetAPI\OrderType();
    $order->setDescription("New Item");
    // Set the customer's Bill To address
    $customerAddress = new AnetAPI\CustomerAddressType();
    $customerAddress->setFirstName("Ellen");
    $customerAddress->setLastName("Johnson");
    $customerAddress->setCompany("Souveniropolis");
    $customerAddress->setAddress("14 Main Street");
    $customerAddress->setCity("Pecan Springs");
    $customerAddress->setState("TX");
    $customerAddress->setZip("44628");
    $customerAddress->setCountry("USA");
    // Set the customer's identifying information
    $customerData = new AnetAPI\CustomerDataType();
    $customerData->setType("individual");
    $customerData->setId("99999456654");
    $customerData->setEmail("EllenJohnson@example.com");
    //Add values for transaction settings
    $duplicateWindowSetting = new AnetAPI\SettingType();
    $duplicateWindowSetting->setSettingName("duplicateWindow");
    $duplicateWindowSetting->setSettingValue("600");
    // Create a TransactionRequestType object
    $transactionRequestType = new AnetAPI\TransactionRequestType();
    $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
    $transactionRequestType->setAmount($amount);
    $transactionRequestType->setOrder($order);
    $transactionRequestType->setPayment($paymentOne);
    $transactionRequestType->setBillTo($customerAddress);
    $transactionRequestType->setCustomer($customerData);
    $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
    $request = new AnetAPI\CreateTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId( $refId);
    $request->setTransactionRequest( $transactionRequestType);
    $controller = new AnetController\CreateTransactionController($request); 
       return view('about');
    }    
}

<?php

namespace Binjuhor\MoMo\Services\Gateways;

use Botble\Ecommerce\Models\Order;
use Botble\Payment\Enums\PaymentStatusEnum;
use Exception;

class MoMoPaymentService
{
    protected $partnerCode;
    protected $accessKey;
    protected $secretKey;

    public function __construct()
    {
        $this->partnerCode = setting('momo_partner_code');
        $this->accessKey = setting('momo_access_key');
        $this->secretKey = setting('momo_secret_key');
    }

    public function makePayment(array $data)
    {
        $momoUrl = config('plugins.momo.general.production');
        if(setting('payment_momo_mode') == 0 ){
            $momoUrl = config('plugins.momo.general.sandbox');
        }

        $endpoint = $momoUrl.'/v2/gateway/api/create';
        $partnerCode = $this->partnerCode;
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $orderInfo = $data['description'];
        $amount = $data['amount'];
        $orderId = time() . "";
        $redirectUrl = $data['callback_url'];
        $ipnUrl = route('payments.momo.ipn');
        $extraData = $data['orders'][0]->id ."";
        $requestId = time() . "";
        $requestType = "captureWallet";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array('partnerCode' => $partnerCode,
            'partnerName' => config('app.name'),
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature);

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if($jsonResult['resultCode'] != 0) {
            die($jsonResult['message']);
        }

        return $jsonResult['payUrl'];
    }

    public function execPostRequest($url, $data): bool|string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function storedData(array $data): void
    {
        $chargeId = $data['orderId'];
        $order = Order::find($data['extraData']);

        if($order !== NULL) {
            $customer = $order->user;
            do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                'amount' => $data['amount'],
                'currency' => 'VND',
                'charge_id' => $chargeId,
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'customer_type' => get_class($customer),
                'payment_channel' => MOMO_PAYMENT_METHOD_NAME,
                'status' => $data['resultCode'] == '0' ? PaymentStatusEnum::COMPLETED : PaymentStatusEnum::PENDING,
            ]);
        }
    }

    public function getPaymentStatus($request): string
    {
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $partnerCode = $request->partnerCode;
        $orderId = $request->orderId;
        $requestId = $request->requestId;
        $amount = $request->amount;
        $orderInfo = $request->orderInfo;
        $orderType = $request->orderType;
        $transId = $request->transId;
        $resultCode = $request->resultCode;
        $message = $request->message;
        $payType = $request->payType;
        $responseTime = $request->responseTime;
        $extraData = $request->extraData;
        $m2signature = $request->signature; //MoMo signate

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime .
            "&resultCode=" . $resultCode . "&transId=" . $transId;

        $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);

        if ($m2signature == $partnerSignature) {
            if ($resultCode == '0') {
                $result = 'success';
            } else {
                $result = 'error';
            }
        } else {
            $result = 'hacked';
        }

        return $result;
    }

    public function supportedCurrencyCodes(): array
    {
        return ['VND'];
    }

    /**
     * This function run on production for IPN
     * Check more here: https://sandbox.momoment.vn/apis/docs/huong-dan-tich-hop/#code-ipn-url
     *
     * @param $request
     * @return string[]
     */
    public function afterPayment( $request ): array
    {
        $paymentStatus = $this->getPaymentStatus($request);
        switch ($paymentStatus) {
            case 'success':
                $response['message'] = 'Capture Payment Success';
                break;
            case 'error':
                $response['message'] = 'Capture Payment Error';
                break;
            case 'hacked':
                $response['message'] = 'This transaction could be hacked, please check your signature and returned signature';
                break;
        }

        return $response;
    }

    public function getToken(array $data)
    {
        $order = Order::find($data['extraData']);
        return $order->token;
    }
}

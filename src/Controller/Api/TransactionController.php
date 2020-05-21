<?php declare(strict_types=1);


namespace App\Controller\Api;


use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TransactionController
 * @package App\Controller\Api
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("/api/transaction/buy", methods={"GET"}, name="route.api.transaction.buy")
     * @throws Exception
     */
    public function buy(): JsonResponse
    {
        $url = $this->generateRCUrl();
        $parseUrl = parse_url($url);
        parse_str($parseUrl['query'], $parseQuery);

        if (!empty($parseQuery) && empty($parseQuery['SignatureValue'])) {
            throw new Exception('В ссылке не найдено значение в ключе "SignatureValue"!');
        }

        $signatureValue = $parseQuery['SignatureValue'];

        return new JsonResponse([
            'url' => $parseUrl,
            'query' => $parseQuery,
        ]);
    }

    public function generateRCUrl()
    {
        // your registration data
        $mrh_login = "test";        // your login here
        $mrh_pass1 = "securepass1"; // merchant pass1 here

        // order properties
        $inv_id = 5;        // shop's invoice number
        // (unique for shop's lifetime)
        $inv_desc = "desc";   // invoice desc
        $out_summ = "5.12";   // invoice summ

        // build CRC value
        $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

        // build URL
        return "https://auth.robokassa.ru/Merchant/PaymentForm/FormMS.js?MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id&Description=$inv_desc&SignatureValue=$crc&IsTest=1";
    }
}

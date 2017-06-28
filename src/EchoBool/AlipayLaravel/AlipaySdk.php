<?php
/**
 * Created by PhpStorm.
 * User: luojinyi
 * Date: 2017/6/27
 * Time: 上午11:48
 */

namespace EchoBool\AlipayLaravel;

use EchoBool\AlipayLaravel\BuilderModel\AlipayTradeCloseContentBuilder;
use EchoBool\AlipayLaravel\BuilderModel\AlipayTradeFastpayRefundQueryContentBuilder;
use EchoBool\AlipayLaravel\BuilderModel\AlipayTradePagePayContentBuilder;
use EchoBool\AlipayLaravel\BuilderModel\AlipayTradeQueryContentBuilder;
use EchoBool\AlipayLaravel\BuilderModel\AlipayTradeRefundContentBuilder;
use EchoBool\AlipayLaravel\Service\AlipayTradeService;

class AlipaySdk
{
    public $aop;
    public $config;

    /**
     * AlipaySdk constructor.
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->aop = new AlipayTradeService($config);
    }

    /**
     * 支付接口 支持自定义数据传输
     * @param $subject
     * @param $body
     * @param $out_trade_no
     * @param $total_amount
     * @param $customData 自定义数据
     * @return bool|提交表单HTML文本|mixed|\SimpleXMLElement|string
     */
    public function tradePagePay($subject, $body, $out_trade_no, $total_amount, $customData)
    {
        $payRequestBuilder = new AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setPassbackParams($customData);
        $response = $this->aop->pagePay($payRequestBuilder, $this->config['return_url'], $this->config['notify_url'], $this->config['trade_pay_type']);

        return $response;
    }

    /**
     * 退款接口
     * @param $out_trade_no//商户订单号，商户网站订单系统中唯一订单号
     * @param $refund_amount//需要退款的金额，该金额不能大于订单金额，必填
     * @param $refund_reason//退款的原因说明
     * @param string $out_request_no//标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
     * @return bool|提交表单HTML文本|mixed|\SimpleXMLElement|\SimpleXMLElement[]|string
     */
    public function tradeRefund($out_trade_no, $refund_amount, $refund_reason, $out_request_no = '')
    {
        $RequestBuilder = new AlipayTradeRefundContentBuilder();

        $RequestBuilder->setOutTradeNo($out_trade_no);
        //$RequestBuilder->setTradeNo($trade_no);
        $RequestBuilder->setRefundAmount($refund_amount);
        $RequestBuilder->setOutRequestNo($out_request_no);
        $RequestBuilder->setRefundReason($refund_reason);

        $response = $this->aop->Refund($RequestBuilder);
        return $response;
    }

    /**
     * 支付交易查询接口，用于查询交易是否交易成功
     * @param $out_trade_no
     * @return bool|提交表单HTML文本|mixed|\SimpleXMLElement|\SimpleXMLElement[]|string
     */
    public function tradePayQuery($out_trade_no)
    {
        $RequestBuilder = new AlipayTradeQueryContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        //$RequestBuilder->setTradeNo($trade_no);

        $response = $this->aop->Query($RequestBuilder);
        return $response;
    }

    /**
     * 退款查询接口
     * @param $out_trade_no//商户订单号，商户网站订单系统中唯一订单号
     * @param $out_request_no//请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号，必填
     * @return bool|提交表单HTML文本|mixed|\SimpleXMLElement|string
     */
    public function refundQuery($out_trade_no, $out_request_no)
    {
        $RequestBuilder=new AlipayTradeFastpayRefundQueryContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        //$RequestBuilder->setTradeNo($trade_no);
        $RequestBuilder->setOutRequestNo($out_request_no);

        $response = $this->aop->refundQuery($RequestBuilder);
        return $response;
    }

    /**
     * 关闭订单接口
     * @param $out_trade_no
     * @return bool|提交表单HTML文本|mixed|\SimpleXMLElement|\SimpleXMLElement[]|string
     */
    public function Close($out_trade_no)
    {
        $RequestBuilder=new AlipayTradeCloseContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        //$RequestBuilder->setTradeNo($trade_no);

        $response = $this->aop->Close($RequestBuilder);
        return $response;
    }
}
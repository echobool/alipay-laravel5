# alipay-laravel5 支付宝电脑网站支付
**根据支付宝最新版 电脑网站支付接口SDK 整合laravel5**


## 安装

首先安装 [Composer](http://getcomposer.org/). 已安装请忽略。
在 `composer.json` 文件中添加:

    "echobool/alipay-laravel5": "dev-master"

然后执行composer进行安装:

    $ composer update -vvv
或直接：

    $ composer install "echobool/alipay-laravel5"


## 支持
支付支持表单提交和Curl后台提交方式

支持交易查询操作

支持退款操作

支持退款查询操作

支持交易关闭操作

## 用法


```php
/**
     * 支付
     * @param Request $request
     * @return mixed
     */
    public function goPay(Request $request)
    {
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = date('YmdHis') . '00045623';
        //订单名称，必填
        $subject = '锁贸通任务ID448';
        //付款金额，必填
        $total_amount = 0.01;
        //商品描述，可空
        $body = 'macbook pro2';

        $customData = json_encode(['model_name' => 'ewrwe', 'id' => 121]);//自定义参数
        $response = Alipay::tradePagePay($subject, $body, $out_trade_no, $total_amount, $customData);
        //输出表单
        return $response['redirect_url'];
    }


    /**
     * 退款
     * @param Request $request
     */
    public function refund(Request $request)
    {
        //商户订单号
        $out_trade_no = $request->get('trade_no');
        $refund_amount = 0.01;
        $refund_reason = '任务取消退款';
        $out_request_no = '201';
        $response = Alipay::tradeRefund($out_trade_no, $refund_amount, $refund_reason, $out_request_no);
        dd($response);
    }

    /**
     * 退款查询
     * @param Request $request
     */
    public function refundQuery(Request $request)
    {
        //商户订单号
        $out_trade_no = $request->get('trade_no');
        $out_request_no = $request->get('out_request_no');

        $response = Alipay::refundQuery($out_trade_no,$out_request_no);
        dd($response);
    }

    /**
     * 交易是否成功查询
     * @param Request $request
     */
    public function tradePayQuery(Request $request)
    {
        //商户订单号
        $out_trade_no = $request->get('trade_no');
        $response = Alipay::tradePayQuery($out_trade_no);
        dd($response);
    }

    /**
     * 交易关闭
     * @param Request $request
     */
    public function tradeClose(Request $request)
    {
        //商户订单号
        $out_trade_no = $request->get('trade_no');
        $response = Alipay::Close($out_trade_no);
        dd($response);
    }
```



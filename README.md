# alipay-laravel5 支付宝电脑网站支付
**根据支付宝最新版 电脑网站支付接口SDK 整合laravel5**


## 安装

首先安装 [Composer](http://getcomposer.org/). 已安装请忽略。
在 `composer.json` 文件中添加:

    "echobool/alipay-laravel5": "dev-master"

然后执行composer进行安装:

    $ composer update -vvv
或直接：

    $ composer require "echobool/alipay-laravel5:dev-master"
在app.php中加上

    EchoBool\AlipayLaravel\AlipayServiceProvider::class,
    
更新配置

    php artisan config:cache
    
发布配置文件

    $ php artisan vendor:publish --provider="EchoBool\AlipayLaravel\AlipayServiceProvider"

如果出现 EchoBool\AlipayLaravel\AlipayServiceProvider not found 则运行下面代码再发布

    $ composer dump-autoload --optimize
    
## 支持
支付支持表单提交和Curl后台提交方式 

当配置文件中 trade_pay_type=>true 时为表单提交 默认CURL提交。

支持交易查询操作

支持退款操作

支持退款查询操作

支持交易关闭操作

## 用法

先将config/alipay-web.php 中各项配置好

```php
//文件头use一下
use EchoBool\AlipayLaravel\Facades\Alipay;

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
    
    /**
         * 异步通知
         * @param Request $request
         */
        public function notify(Request $request)
        {
            $result = Alipay::notify($_POST);
            /* 实际验证过程建议商户添加以下校验。
           1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
           2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
           3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
           4、验证app_id是否为该商户本身。
           */
            if ($result) {//验证成功
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //请在这里加上商户的业务逻辑程序代
    
    
                //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    
                //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
    
                //商户订单号
    
                $out_trade_no = $_POST['out_trade_no'];
    
                //支付宝交易号
    
                $trade_no = $_POST['trade_no'];
    
                //交易状态
                $trade_status = $_POST['trade_status'];
    
    
                if ($_POST['trade_status'] == 'TRADE_FINISHED') {
    
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序
    
                    //注意：
                    //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序
                    //注意：
                    //付款完成后，支付宝系统发送该交易状态通知
                }
                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
                echo "success";    //请不要修改或删除
            } else {
                //验证失败
                echo "fail";
    
            }
        }
    
    
        /**
         * 同步通知 即支付成功后跳转到return_url 上时进行验证  如果支付方式是CURL方式将不会跳转 请注意
         * @param Request $request
         */
        public function returnUrl(Request $request)
        {
            $result = Alipay::notify($_GET);
            /* 实际验证过程建议商户添加以下校验。
                1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
                2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
                3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
                4、验证app_id是否为该商户本身。
             */
    
            if ($result) {//验证成功
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //请在这里加上商户的业务逻辑程序代码
    
                //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
                //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
    
                //商户订单号
                $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
    
                //支付宝交易号
                $trade_no = htmlspecialchars($_GET['trade_no']);
    
                echo "验证成功<br />支付宝交易号：" . $trade_no;
    
                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            } else {
                //验证失败
                echo "验证失败";
            }
        }
```



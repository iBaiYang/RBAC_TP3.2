<?php
class Config{
    private $cfg = array(
        //接口请求地址，固定不变，无需修改
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
        //测试商户号，商户需改为自己的
        //'mchId'=>'7551000001',
        'mchId'=>'102510229226',
        //测试密钥，商户需改为自己的
        //'key'=>'9d101c97133837e13dde2d32a5054abb',
        'key'=>'0bb9d9e610890f6e4991f131618744d3',
        //版本号默认为2.0
        'version'=>'2.0'
    );

    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>
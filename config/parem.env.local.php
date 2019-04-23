<?php

$env = [
    'path' => '/data/image_doc', //
    'user_path' => '/data/user_doc', //
    'logPath' => '/data/log', //
    'lib'=>[
        'aliyun'=>[
            'sms'=>[
                'product'=>'Dysmsapi',
                'domain'=>'dysmsapi.aliyuncs.com',
                'accessKeyId'=>'LTAItvqWOCfp8oD3',
                'accessKeySecret'=>'DWLwwx0v29DlxS6Wt1rzAPJplP0sAE',
                'region'=>'cn-hangzhou',
                'endPointName'=>'cn-hangzhou',
            ]
        ]
    ],
];
return $env;

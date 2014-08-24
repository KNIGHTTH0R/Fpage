<?php
return array(
	'fpageConf'=> array(
	
			'graphurl'=>'https://graph.facebook.com/',
			'pageid' => '270939905654',
			'appid' =>'578779668820938',
            'appsecret' => 'a5a86216dfefaa222661e917a8fa9f10'
),
		'fsocket' => array(
				'adapter'      => 'Zend\Http\Client\Adapter\Socket',
				'ssltransport' => 'tls'
		),
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'myapp',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            array(
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ),
        ),
    ),
);
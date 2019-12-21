<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016101600697106",

		//商户私钥
		'merchant_private_key' => "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC0RSjVxjXIT7+Y+QTafkzwR4gsX6BIpelbFClO8YJuClCBfAZB19CxLzb35n8PJAyFDU6jzbkkvGbweC8x/uFlmtQnCNjaC2cFbsyNMrbDYuWgmSCmiB7PJYNtlGfbM4iolybDV7iTaFJFeAQW62HKTJNFmIr4RXAuzGE9gnFnhTYwjav+Nrrz8F2bJ2RcW9ah1F0FwFNlw0CrKhKmgcR3PRfI9gfDSyR/7Kx73Yjd4vWJkvScBhQa++XfoTbKnmWxlXkwfxWCUhTCnGAE9vWjWXLEH3HTL1JdKSj4vyA2NdRMUEDowHtp9K3+xkhC7RjMzfr0deV7K2+qHEHFWhO3AgMBAAECggEAIXFLGA2DYLvasYhQ7C/OGi1LB+BdndyZo/njh++p4LwtHzhg+MoOvzAhJHAwViC76dsEIcNCe5wwBKBnMQE102GbxfoQMLM403swm2kzGqA4NUjzE7Hg9VDiRuvnMHUo/w27dTXK2h3aVWTkbVQRejQmhhy8ez52h1gHGiAe5bMy5cGNuxLLnGkx74Vi9FXVcQgU/CCdxfn6j/6ga6w417wZs5or2nKNxMjfSTjAJpm8mXHCYwlL8Jbme9Tq96KDH6MPFNXoEzYjQam82YDr3XLjw2/OG+Qj0/ZshmYHTKFTJfC3kLFWdreGOAgw2VrX33ToSoT25jEpF85oY4qagQKBgQDm/gUnqhT7cjmw0GZACx7mh83C98CNniZ/KmIkBvuAkHMa2dLa7FqDYW73xu5JZzpytuSDYRO+CmLsXgRrpCBdGZb/OKRKqeJu/8dvHhBJq68r2+WM4Vp7c5Gx7YX3IP4ksi+izI2ZlPDqjOgBvqaNgm69KSYzZvclXeG6PyRcIQKBgQDHyV6gRyZzQhtxMCzFRqa6eYEQTSI48bAIKvkx0/DMWI5pQMDlkqAbN12oMo3wmd3Mv0KYtvlDn0GG8htS6I5mNLtVoKlgJtgzxH7PkKj9nXyvoh5SBYjgAayt5hGU1YffSd1KZ07R/ytURyhqnc1RwkqHChL7afxLyx+IB9o01wKBgQCKDUcHbZ8cqCfShLqXjA3ruT5AR9HM0bgwSCRfY8zsCWjBo21haeCupIytiTbgp9FGvgfUfTBPY+W7XnVF39F30tNrtW57MQ0jSbrnrhAN9425qLKIgCaToX5x2IM4L3+0bsoJgjcekuVpSM1gPZFNAactZmpuThtnObSumw4gAQKBgHONO4a2QD3voVGhDHP5fhtihUx5YG7REwBWYpT4QVDfc1bbFfZyDNpQ9oF+4+uiAtAWWx1azubWqJ61TypvyVTB8QwAhZZQQUIHx1SkFanCOciXmrPT8aumLErUQ5zyt8hkv3H2OHdq/5OaKH0p0gQUvOhH4ly5myv3SsBx0jo3AoGBAN3IfuUgb4KCTiYhMGhZ1efai8loZ0B2N996IERm81YtSKI22BsYFp7NyqpYyGMgfgFWsijyRv6yF7dPBIEn7xrv6CvY8M7kDss+LnpAqrRMYvNRurMGqRCRey9PGItDY4tsXOhPNzdbvxbJ3LdpNGAMV5Fn4Q7RyrjwtULWAsD+",
		
		//异步通知地址
		'notify_url' => "http://www.pyg.com/home/order/notify",
		
		//同步跳转
		'return_url' => "http://www.pyg.com/home/order/callback",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsfObM6BpA3H/1FVYuEY96wSGDlKsXHZU4J5BO0R7NfjWspwUMYDgLN5pq9SRyiAeBHLyj62buKGo2z8scy/KLYU7XXl1MFyt1Eo3KzpC+W1NIxofzEce6dpbq4jj5teBJ4sXBOp8EDoDNyt8BAtX/nYucVWQaq3dvldmiyboFo1WaO3dH+qKiAEo5s8te38dFtfoTeoNSI8wM6NVnhPwuajxLe8nqsmLgufAA46Z5sAwSsYZQugzI2oEp0ahdJb7AijgYqCdlSYK0EYrP41WTRkbD2T2e52M8QkgL3FoJQdLwrFWbaVYyLUAlqLn+gAHi1La0J5/lk9j0U5MHe1pOwIDAQAB",
);
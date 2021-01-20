<?php
define('_JSHOP_RK_MERCHANT_URL', 'URL мерчанта');
define('_JSHOP_RK_MERCHANT_URL_DESCR', 'URL-адрес для оплаты');
define('_JSHOP_RK_MERCHANT_ID', 'Идентификатор магазина');
define('_JSHOP_RK_MERCHANT_ID_DESCR', 'Идентификатор магазина, зарегистрированного в системе Roskassa');
define('_JSHOP_RK_SECRET_KEY1', 'Секретный ключ');
define('_JSHOP_RK_SECRET_KEY_DESCR', 'Секретный ключ мерчанта');
define('_JSHOP_RK_LOG_FILE', 'Путь к файлу журнала');
define('_JSHOP_RK_LOG_FILE_DESCR', 'Путь к файлу журнала для платежей через Roskassa (например, /roskassa_orders.log)');
define('_JSHOP_RK_IP_FILTER', 'Фильтр IP');
define('_JSHOP_RK_IP_FILTER_DESCR', 'IP фильтр обработчика платежа, наши ip:');
define('_JSHOP_RK_EMAIL_ERR', 'Электронная почта для ошибок');
define('_JSHOP_RK_EMAIL_ERR_DESCR', 'Электронная почта для отправки ошибки оплаты');
define('_JSHOP_RK_TRANSACTION_PENDING_DESCR', 'Cтатус нового заказа, при оплате через Roskassa');
define('_JSHOP_RK_TRANSACTION_SUCCESS_DESCR', 'Cтатус успешного заказа, при оплате через Roskassa');
define('_JSHOP_RK_TRANSACTION_FAILED_DESCR', 'Cтатус неуспешного заказа, при оплате через Roskassa');
define('_JSHOP_RK_SUCCESS_URL', 'URL успешной оплаты');
define('_JSHOP_RK_SUCCESS_URL_DESCR', 'URL, на который нужно направить клиента после успешной оплаты через Roskassa');
define('_JSHOP_RK_FAIL_URL', 'URL неуспешной оплаты');
define('_JSHOP_RK_FAIL_URL_DESCR', 'URL, на который нужно направить клиента после неуспешной оплаты через Roskassa');
define('_JSHOP_RK_STATUS_URL', 'URL обработчика');
define('_JSHOP_RK_STATUS_URL_DESCR', 'URL для подтверждения и проверки заказа, для успешного зачисления денежных средств при оплате через Roskassa');
define('_JSHOP_RK_MSG_NOT_VALID_IP', ' - IP сервера уведомлений не является доверенным');
define('_JSHOP_RK_MSG_VALID_IP', '   доверенные IP: ');
define('_JSHOP_RK_MSG_THIS_IP', '   IP текущего сервера: ');
define('_JSHOP_RK_MSG_HASHES_NOT_EQUAL', ' - не совпадают цифровые подписи');
define('_JSHOP_RK_MSG_WRONG_AMOUNT', ' - неправильная сумма');
define('_JSHOP_RK_MSG_WRONG_CURRENCY', ' - неправильная валюта');
define('_JSHOP_RK_MSG_STATUS_FAIL', ' - статус платежа не является success');
define('_JSHOP_RK_MSG_ERR_REASONS', 'Не удалось провести платёж через систему Roskassa по следующим причинам:');
define('_JSHOP_RK_MSG_SUBJECT', 'Ошибка оплаты');
?>
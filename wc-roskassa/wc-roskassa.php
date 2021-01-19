<?php



/*

  Plugin Name: Integration of Roskassa payment systems with WooCommerce

  Plugin URI: https://roskassa.net

  Description: Module for accepting payments in the RosKassa payment system.

  Version: 0.2

  Tags: WooCommerce, WordPress, Gateways, Payments, Payment, Money, WooCommerce, WordPress, Plugin, Module, Store, Modules, Plugins, Payment system, Website, RosKassa



  Author: SMOSERVICE MEDIA

  Author URI: https://smoservice.media/

  Copyright: © 2020 SMOSERVICE MEDIA.

  License: GNU General Public License v3.0

  License URI: http://www.gnu.org/licenses/gpl-3.0.html



 */ 



if (!defined('ABSPATH'))

{

    exit;

}



add_action('plugins_loaded', 'woocommerce_rk', 0);







function woocommerce_rk()

{

    if (!class_exists('WC_Payment_Gateway'))

    {

        return;

    }



    if (class_exists('WC_RK'))

    {

        return;

    }



    class WC_RK extends WC_Payment_Gateway

    {

        public function __construct()

        {

            global $woocommerce;



            $plugin_dir = plugin_dir_url(__FILE__);



            $this->id = 'roskassa';

            $this->source = 'WP 0.2.2';

            $this->icon = apply_filters('woocommerce_rk_icon', $plugin_dir . 'icon.svg');

            $this->has_fields = false;

            $this->init_form_fields();

            $this->init_settings();

            $this->title = $this->get_option('title');

            $this->rk_url = $this->get_option('rk_url');

            $this->rk_shop_id = $this->get_option('rk_shop_id');

            $this->rk_secret_key = $this->get_option('rk_secret_key');

            $this->email_error = $this->get_option('email_error');

            $this->log_file = $this->get_option('log_file');

            $this->test_mode = $this->get_option('test_mode');



            $this->method_title = 'Интернет-эквайринг Роскасса';

            $this->method_description = 'Интернет-эквайринг РосКасса (прием платежей), интеграция с другими платежными системами.';



            add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

            add_action('woocommerce_api_wc_' . $this->id, array($this, 'check_ipn_response'));



            if (!$this->is_valid_for_use())

            {

                $this->enabled = false;

            }

        }



        function is_valid_for_use()

        {

            return true;

        }





        public function admin_options()

        {

            ?>

          <h3><?php _e('РосКасса', 'woocommerce'); ?></h3>

          <p><?php _e('Настройка приема электронных платежей через Роскасса. ',' woocommerce'); ?></p>



            <?php if ( $this->is_valid_for_use() ) : ?>

          <table class="form-table">

              <?php $this->generate_settings_html(); ?>

          </table>



        <?php else : ?>

          <div class="inline error">

            <p>

              <strong><?php _e('Gateway disabled', 'woocommerce'); ?></strong>:

                <?php _e('Роскасса не поддерживает валюты ваших магазинов. ',' woocommerce' ); ?>

            </p>

          </div>

        <?php

        endif;

        }



        function init_form_fields()

        {

            $this->form_fields = array(

                'enabled' => array(

                    'title' => __('Включить / Выключить', 'woocommerce'),

                    'type' => 'checkbox',

                    'label' => __('Включить', 'woocommerce'),

                    'default' => 'yes'

                ),

                'title' => array(

                    'title' => __('Название', 'woocommerce'),

                    'type' => 'text',

                    'description' => __( 'Это имя, которое видит пользователь при выборе способа оплаты. ',' woocommerce' ),

                    'default' => __('РосКасса', 'woocommerce')

                ),

                'rk_url' => array(

                    'title' => __('URL мерчанта', 'woocommerce'),

                    'type' => 'text',

                    'description' => __('URL для оплаты в системе Роскасса', 'woocommerce'),

                    'default' => 'https://pay.roskassa.net/'

                ),

                'rk_shop_id' => array(

                    'title' => __('ID мерчанта', 'woocommerce'),

                    'type' => 'text',

                    'description' => __('Публичный КЛЮЧ магазина, зарегистрированного в системе «Роскасса».<br/>Вы можете узнать это в <a href="https://my.roskassa.net/shop-settings/"> Аккаунт Роскасса </a>: "Аккаунт -> Настройки".', 'woocommerce'),

                    'default' => ''

                ),

                'rk_secret_key' => array(

                    'title' => __('Секретный ключ', 'woocommerce'),

                    'type' => 'password',

                    'description' => __('Секретный ключ для уведомления об исполнении платежа, <br/> который используется для проверки целостности полученной информации <br/> и однозначного идентифицирования отправителя. <br/> Должен соответствовать секретному ключу, указанному в <a href="https://my.roskassa.net/shop-settings/">Аккаунт Роскасса </a>: "Аккаунт -> Настройки".', 'woocommerce'),

                    'default' => ''

                ),

                'log_file' => array(

                    'title' => __('Путь к файлу журнала платежей через Роскассу (например, /roskassa_orders.log)', 'woocommerce'),

                    'type' => 'text',

                    'description' => __('Если путь не указан, то журнал не пишется', 'woocommerce'),

                    'default' => ''

                ),

                'email_error' => array(

                    'title' => __('Электронная почта для ошибок', 'woocommerce'),

                    'type' => 'text',

                    'description' => __('Электронная почта для отправки ошибок платежа', 'woocommerce'),

                    'default' => ''

                ),

                'test_mode' => array(

                    'title' => __('Тестовый режим', 'woocommerce'),

                    'type' => 'checkbox',

                    'label' => __('Включить', 'woocommerce'),

                    'default' => 'yes'

                ),

            );

        }



        function payment_fields()

        {

            if ($this->description)

            {

                echo wpautop(wptexturize($this->description));

            }

        }



        public function generate_form($order_id)

        {

            global $woocommerce;



            $order = new WC_Order($order_id);



            $form_data = array(

                'shop_id'=>$this->rk_shop_id,

                'amount'=>round($order->order_total, 2),

                'order_id'=>$order_id,

                'currency'=>$order->order_currency == 'RUR' ? 'RUB' : $order->order_currency,

            );



            if ($this->get_option('test_mode') == 'yes') {

                $form_data['test'] = 1;

            }



            ksort($form_data);

            $str = http_build_query($form_data);

            $form_data['sign'] = md5($str . $this->rk_secret_key);



            $form_data['source'] = $this->source;



            $form =  '<form method="GET" action="' . $this->rk_url . '">';

            foreach ($form_data as $k=>$v) {

                $form .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';

            }

            $form .= '	<input type="submit" name="submit" value="Оплатить" /></form>';



            return $form;

        }



        function process_payment($order_id)

        {

            $order = new WC_Order($order_id);



            return array(

                'result' => 'success',

                'redirect'	=> add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))

            );

        }



        function receipt_page($order)

        {

            echo '<p>'.__('Спасибо за ваш заказ, пожалуйста, нажмите кнопку ниже, чтобы оплатить.', 'woocommerce').'</p>';

            echo $this->generate_form($order);

        }



        function check_ipn_response()

        {

            global $woocommerce;



            if (isset($_GET['rk']) && $_GET['rk'] == 'result')

            {

                if (isset($_POST["order_id"]) && isset($_POST["sign"]))

                {

                    $err = false;

                    $message = '';



                    // logging



                    $log_text =

                        "--------------------------------------------------------\n" .

                        "public key         " . sanitize_text_field($_POST['public_key']) . "\n" .

                        "amount             " . sanitize_text_field($_POST['amount']) . "\n" .

                        "order id           " . sanitize_text_field($_POST['order_id']) . "\n" .

                        "currency           " . sanitize_text_field($_POST['currency']) . "\n" .

                        "test               " . sanitize_text_field($_POST['test']) . "\n" .

                        "sign               " . sanitize_text_field($_POST['sign']) . "\n\n";



                    $log_file = $this->log_file;



                    if (!empty($log_file))

                    {

                        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $log_file, $log_text, FILE_APPEND);

                    }



                    // verification of digital signature and ip



                    // we must use all POST request for sign, $data never user after

                    $data = $_POST;

                    unset($data['sign']);

                    ksort($data);

                    $str = http_build_query($data);

                    $sign_hash = md5($str . $this->rk_secret_key);



                    if ($_POST["sign"] != $sign_hash)

                    {

                        $message .= " - цифровые подписи не совпадают\n";

                        $err = true;

                    }



                    if (!$err)

                    {

                        // loading order



                        $order = new WC_Order(sanitize_text_field($_POST['order_id']));

                        $order_curr = ($order->order_currency == 'RUR') ? 'RUB' : $order->order_currency;

                        $order_amount = number_format($order->order_total, 2, '.', '');



                        // checking amount and currency



                        if (number_format($_POST['amount'], 2, '.', '') !== $order_amount)

                        {

                            $message .= " - неправильная сумма\n";

                            $err = true;

                        }



                        if ($_POST['currency'] !== $order_curr)

                        {

                            $message .= " - неправильная валюта\n";

                            $err = true;

                        }



                        // check status



                        if (!$err)

                        {

                            if ($order->post_status != 'wc-processing')

                            {

                                $order->update_status('processing', __('Платеж успешно оплачен', 'woocommerce'));

                                WC()->cart->empty_cart();

                            }

                        }

                    }



                    if ($err)

                    {

                        $to = $this->email_error;



                        if (!empty($to))

                        {

                            $message = "Не удалось осуществить платеж через систему Роскасса по следующим причинам:\n\n" . $message . "\n" . $log_text;

                            $headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n" .

                                       "Content-type: text/plain; charset=utf-8 \r\n";

                            mail($to, 'Ошибка оплаты', $message, $headers);

                        }



                        if (!empty($log_file))

                        {

                            file_put_contents($_SERVER['DOCUMENT_ROOT'] . $log_file, "ERR: $message", FILE_APPEND);

                        }



                        die(sanitize_text_field($_POST['order_id']) . '|error');

                    }

                    else

                    {

                        die(sanitize_text_field($_POST['order_id']) . '|success');

                    }

                }

                else

                {

                    wp_die('Ошибка запроса IPN');

                }

            }

            else if (isset($_GET['rk']) && ($_GET['rk'] == 'calltrue' || $_GET['rk'] == 'callfalse'))

            {

                WC()->cart->empty_cart();

                $order = new WC_Order(sanitize_text_field($_GET['order_id']));

                wp_redirect($this->get_return_url($order));

            }

        }

    }



    function add_rk_gateway($methods)

    {

        $methods[] = 'WC_RK';

        return $methods;

    }



    add_filter('woocommerce_payment_gateways', 'add_rk_gateway');



    function rk_settings_link( $links ) {

        $settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=roskassa">' . __( 'Settings' ) . '</a>';





        array_push( $links, $settings_link );

        return $links;

    }

    $plugin = plugin_basename( __FILE__ );

    add_filter( "plugin_action_links_$plugin", 'rk_settings_link' );

}

?>
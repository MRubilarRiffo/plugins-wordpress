<?php
/*
Plugin Name: Notificador de Productos para WooCommerce
Plugin URI: http://casanovedad.com
Description: Muestra una notificación de un producto al azar cada 7 segundos, excepto en la página de finalizar compra.
Version: 1.0
Author: Jacke
Author URI: http://casanovedad.com
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Salir si se accede directamente
}

/**
 * Verificar si WooCommerce está activo
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    function mi_notificador_productos() {
        if ( ! is_checkout() ) {
            wp_enqueue_script('mi-notificador-js', plugin_dir_url(__FILE__) . 'notificador.js', array('jquery'), '1.0', true);
            wp_enqueue_style('mi-notificador-css', plugin_dir_url(__FILE__) . 'notificador.css', array(), '1.0', 'all');
            wp_localize_script('mi-notificador-js', 'ajaxurl', admin_url('admin-ajax.php'));
        }
    }

    add_action('wp_enqueue_scripts', 'mi_notificador_productos');

    function truncate_utf8($string, $length, $etc = '...') {
        if ($length == 0) return '';

        if (mb_strlen($string, 'UTF-8') > $length) {
            $length -= min($length, mb_strlen($etc, 'UTF-8'));
            return mb_substr($string, 0, $length, 'UTF-8') . $etc;
        } else {
            return $string;
        }
    }

    function cargar_datos_producto() {
        $nombres = array('Andrés', 'Diego', 'Matías', 'Joaquín', 'Nicolás', 'Felipe', 'Ignacio', 'Francisco', 'Sebastián', 'Gabriel', 'Benjamín', 'Maximiliano', 'Pedro', 'Javier', 'Cristián', 'José', 'Antonio', 'Héctor', 'Raúl', 'Ricardo', 'Isidora', 'Fernanda', 'Valentina', 'Camila', 'Constanza', 'Francisca', 'Daniela', 'Valeria', 'Antonia', 'Catalina', 'Isabella', 'Sofía', 'Fabiola', 'Carolina', 'Florencia', 'Paula', 'Macarena', 'María José', 'Alejandra', 'Claudia', 'Victoria', 'Paulina', 'Natalia', 'Margarita', 'Verónica', 'Ximena', 'Rocío'); // Array con nombres de mujer
        $nombre_aleatorio = $nombres[array_rand($nombres)];

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
            'orderby' => 'rand'
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            $query->the_post();

            $nombre_producto = truncate_utf8(get_the_title(), 37);

            $response = array(
                'nombre' => $nombre_producto,
                'imagen' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                'url' => get_the_permalink(),
                'nombre_azar' => $nombre_aleatorio
            );
            wp_send_json($response);
        }
        wp_die();
    }
    
    add_action('wp_ajax_nopriv_obtener_producto_aleatorio', 'cargar_datos_producto');
    add_action('wp_ajax_obtener_producto_aleatorio', 'cargar_datos_producto');
}
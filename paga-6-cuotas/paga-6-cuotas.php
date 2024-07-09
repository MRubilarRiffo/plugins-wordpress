<?php
/*
Plugin Name: Paga en 6 cuotas
Plugin URI: http://casanovedad.com
Description: Muestra el precio de un producto dividido en 6 cuotas..
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
    function mostrar_precio_seis_cuotas() {
        if (is_product()) {
            wp_enqueue_style('6-cuotas-css', plugin_dir_url(__FILE__) . 'paga-6-cuotas.css', array(), '1.0', 'all');

            global $product;

            // Obtenemos el precio del producto
            $precio_producto = $product->get_price();

            // Calculamos el precio por cuota
            $precio_por_cuota = round($precio_producto / 6);

            // Mostramos el precio por cuota
            echo '<p class="jacke-paga-6-cuotas">6 cuotas de ' . wc_price($precio_por_cuota) . ' c/u</p>';
        }
    }
    add_action('woocommerce_single_product_summary', 'mostrar_precio_seis_cuotas', 25);
} else {
    // Mostrar un mensaje de advertencia si WooCommerce no está instalado o activado
    add_action('admin_notices', 'woocommerce_no_instalado_advertencia');
    function woocommerce_no_instalado_advertencia() {
        echo '<div class="error"><p>El plugin "WooCommerce 6 Cuotas Plugin" requiere que WooCommerce esté instalado y activado.</p></div>';
    }
}
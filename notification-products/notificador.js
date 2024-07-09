jQuery(document).ready(function($) {
    var notificacionVisible = false;

    function mostrarNotificacion() {
        if (notificacionVisible) {
            return;
        }

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'obtener_producto_aleatorio'
            },
            success: function(response) {
                var notificacion = '<div class="jacke-mi-notificador" id="jacke-notificacionProducto">' +
                                        '<div class="jacke-image">' +
                                            '<img src="' + response.imagen + '" alt="' + response.nombre + '">' +
                                        '</div>' +
                                        '<div class="jacke-info">' +
                                            '<p class="jacke-name">' + response.nombre_azar + ' compró</p>' +
                                            '<p class="jacke-name-product">' + response.nombre + '</p>' +
                                            '<p class="jacke-time">Recientemente</p>' +
                                        '</div>' +
                                   '</div>';
                $('body').append(notificacion);
                // Añadir evento click para redirigir
                $('#jacke-notificacionProducto').on('click', function() {
                    window.location.href = response.url;
                });

                notificacionVisible = true;

                setTimeout(function() {
                    $('#jacke-notificacionProducto').remove();
                    notificacionVisible = false;
                    setTimeout(mostrarNotificacion, 7000);
                }, 6000);
            }
        });
    }

    setTimeout(mostrarNotificacion, 7000);
});
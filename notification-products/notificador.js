jQuery(document).ready(function($) {
    var notificacionVisible = false;

    // Obtener el ID del producto desde clase "postid-XXXXX"
    var match = $('body').attr('class').match(/postid-(\d+)/);
    var productoID = match ? match[1] : null;

    function mostrarNotificacion() {
        if (notificacionVisible || !productoID) {
            return;
        }

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'obtener_producto_aleatorio',
                producto_id: productoID
            },
            success: function(response) {
                if (!response || response.success === false) return;

                var notificacion = '<div class="jacke-mi-notificador" id="jacke-notificacionProducto">' +
                                        '<div class="jacke-image">' +
                                            '<img src="' + response.imagen + '" alt="' + response.nombre + '">' +
                                        '</div>' +
                                        '<div class="jacke-info">' +
                                            '<p class="jacke-name"><span class="jacke-bold">' + response.nombre_azar + '</span> compró</p>' +
                                            '<p class="jacke-name-product">' + response.nombre + '</p>' +
                                            '<p class="jacke-time">Recientemente</p>' +
                                        '</div>' +
                                   '</div>';
                $('body').append(notificacion);

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

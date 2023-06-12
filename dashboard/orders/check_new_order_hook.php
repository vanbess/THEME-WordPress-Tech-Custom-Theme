<?php


defined('ABSPATH') ?: exit();

add_action('wp_footer', function () {

    // get current page slug
    $current_page_slug = get_post_field('post_name', get_post());

    // array of pages to show notification on
    $pages = [
        'dashboard',
        'shop-orders',
        'qr-code',
        'account',
        'users',
        'products',
    ];

    // if current page is not in pages array, return
    if (!in_array($current_page_slug, $pages)) {
        return;
    }

?>

    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div class="modal fade " id="new-order-notication" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">You have new orders waiting!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You have received a new order!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" onclick="goToOrders()" data-bs-dismiss="modal">Thanks!</button>
                </div>
            </div>
        </div>
    </div>

    <!-- add script to check for new orders every 60 seconds -->
    <script>
        jQuery(function($) {

            // go to orders page function
            function goToOrders() {
                window.location.href = '<?php echo get_site_url() . '/dashboard/shop-orders' ?>';
            }

            // notification sound function
            function playNotificationSound() {

                // Create an AudioContext
                const audioContext = new(window.AudioContext || window.webkitAudioContext)();

                // Load the audio file
                const audioFile = '<?php echo EXTECH_URI . '/assets/sounds/sales2.wav' ?>';

                // Create a buffer source node
                const source = audioContext.createBufferSource();

                // Fetch the audio file
                fetch(audioFile)
                    .then(response => response.arrayBuffer())
                    .then(arrayBuffer => audioContext.decodeAudioData(arrayBuffer))
                    .then(audioBuffer => {
                        // Assign the decoded audio data to the source node
                        source.buffer = audioBuffer;

                        // Connect the source node to the audio context destination
                        source.connect(audioContext.destination);

                        // Play the sound
                        source.start(0);
                    })
                    .catch(error => {
                        console.error('Error loading or playing the notification sound:', error);
                    });

            }

            // pulsing animation on order count badge
            $('#new_orders').addClass('animate__animated animate__pulse animate__infinite');

            // function to get specific cookie by cookie name
            function getCookie(name) {
                const matches = document.cookie.match(new RegExp(
                    "(?:^|; )" + name.replace(/([.$?*|{}()[]\\\/\+^])/g, '\\$1') + "=([^;]*)"
                ));

                return matches ? decodeURIComponent(matches[1]) : undefined;
            }

            // check for new_order_notification cookie every 5 seconds and show new order notification modal if cookie is true
            setInterval(function() {

                // get cookie
                var new_order_notification = getCookie('new_order_notification');

                // if cookie is true, show modal
                if (new_order_notification == 'true') {

                    // play notification sound
                    playNotificationSound();

                    // show new order notification modal
                    $('#new-order-notication').modal('show');

                    // clear the cookie or reset the value
                    document.cookie = "new_order_notification=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                }

            }, 5000);

        });
    </script>

<?php });


/**
 * Hook to woocommerce_new_order to check for new orders and set cookie
 */
add_action('woocommerce_new_order', function ($order_id) {
    // set cookie to true
    setcookie('new_order_notification', 'true', time() + 3600, "/");
}, 10, 1);


?>
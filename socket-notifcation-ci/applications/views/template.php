<div id="topNotificationHTMLRender"></div>

<script src="<?php echo ( BROADCAST_URL . '/socket.io/socket.io.js');?> "></script>
<script>
$(document).ready(function() {

    var socket = io.connect("<?php echo BROADCAST_URL;?>");

    var scoketBroadCastNotify = function( broadcastNotifyURL ) {

        $.ajax({
            url: broadcastNotifyURL,
            error: function(e) { console.log('brocast error:', e) },
            success: function( response ) { $("#topNotificationHTMLRender").html( response ) },
            dataType: 'html',
            type: 'POST'
        });
    }

    socket.on('connect', function () {

        socket.on('myBroadCastNotify', function (data) {
            
            console.log('broadcast', data)

            var broadcastNotifyURL = "<?php echo base_url('url/myapi/socketBroadcastNotify');?>";
            scoketBroadCastNotify( broadcastNotifyURL )
        });

        socket.on('disconnect', function () { console.log('disconnected') });
    });
});
</script>
</body>
</html>
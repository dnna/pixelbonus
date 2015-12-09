$(function() {
    $(document).ready(function() {
        $('#qr-iframe').load(function() {
            document.getElementById('qr-iframe').contentWindow.print();
        });
    });
});
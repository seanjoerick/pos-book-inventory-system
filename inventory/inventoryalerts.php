<?php
function inventoryAlerts()
{
    if (isset($_SESSION["senior_strands"])) {
        // Display SweetAlert based on the session variable
        echo "<script>
            swal({
                title: 'Success',
                text: '{$_SESSION["senior_strands"]}',
                icon: 'success',
                button: 'OK',
            });
        </script>";

        // Unset the session variable to prevent showing the alert again on page refresh
        unset($_SESSION["senior_strands"]);
    }
}

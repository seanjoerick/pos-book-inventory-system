<?php

function staffadminalerts()
{
  if (isset($_SESSION["admin_status_update_success"])) {
    $icon = "error";
    $title = "Error";
    $text = "An error occurred.";

    if ($_SESSION["admin_status_update_success"]) {
      $icon = "success";
      $title = "Success";
      $text = $_SESSION["admin_status_update_message"];

      // If the success message contains "active," display a specific SweetAlert
      if (stripos($text, 'active') !== false) {
        echo '<script>
                    Swal.fire({
                        icon: "' . $icon . '",
                        title: "' . $title . '",
                        text: "' . $text . '",
                    });
                </script>';
      }
    } else {
      // Display generic error SweetAlert
      echo '<script>
                Swal.fire({
                    icon: "' . $icon . '",
                    title: "' . $title . '",
                    text: "' . $text . '",
                });
            </script>';
    }
    // Unset the session variables
    unset($_SESSION["admin_status_update_success"]);
    unset($_SESSION["admin_status_update_message"]);
  } elseif (isset($_SESSION["user_added"])) {
    $icon = "error";
    $title = "Error";
    $text = "An error occurred.";

    switch ($_SESSION["user_added"]) {
      case "success":
        $icon = "success";
        $title = "Success";
        $text = "Added successfully.";
        break;

      case "error":
        $text = "Error adding user.";
        break;

      case "error_username_exists":
        $text = "Username already exists. Please choose another one.";
        break;

      case "error_user_exists":
        $text = "User with the same first name and last name already exists.";
        break;

      case "error_invalid_name":
        $text = "Invalid characters in first name or last name. Please use only letters.";
        break;

      case "error_invalid_username":
        $text = "Invalid characters in the username. Please use only letters.";
        break;

      case "error_short_password":
        $text = "Password is too short. It must be at least 6 characters long.";
        break;
    }

    // Display SweetAlert
    echo '<script>
            Swal.fire({
                icon: "' . $icon . '",
                title: "' . $title . '",
                text: "' . $text . '",
            });
        </script>';

    // Unset the session variable
    unset($_SESSION["user_added"]);
  } elseif (isset($_SESSION["update_password_success"])) {
    // Display SweetAlert for successful password update
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "' . $_SESSION["update_password_success"] . '",
        });
    </script>';
    unset($_SESSION["update_password_success"]); // Unset the session variable
  } elseif (isset($_SESSION["update_password_error"])) {
    // Display SweetAlert for password update error
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "' . $_SESSION["update_password_error"] . '",
        });
    </script>';
    unset($_SESSION["update_password_error"]); // Unset the session variable
  } elseif (isset($_SESSION["update_password_empty"])) {
    // Display SweetAlert for empty password error
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "' . $_SESSION["update_password_empty"] . '",
        });
    </script>';
    unset($_SESSION["update_password_empty"]); // Unset the session variable
  }
}

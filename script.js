// <!--------------------------------- JavaScript code here---------------------------------->
// <!--------------------------------- JavaScript code here---------------------------------->
// Function to confirm logout
function confirmLogout() {
  var confirmLogout = confirm("Are you sure you want to logout?");

  if (confirmLogout) {
    window.location.href = "logout.php";
  }

  return false;
}

// Attach the confirmLogout function to the logout link
$(".logout").on("click", function () {
  return confirmLogout();
});
// <!---------------------------------Function yan to show/hide fields based on the selected book type---------------------------------->

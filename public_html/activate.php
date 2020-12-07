<div class="mx-auto p-0 h-100 d-flex flex-column" style="color: #fff;">
<?php
//page title
        $page_title = "Aeki - Activate Account";
        //page background
        $bg = "none";
        $headerShadow = true;
        $cover = true;
require 'includes/config.inc.php';
include 'includes/header.html';

?>

<main class="text-center cover-container cover-head mx-auto mt-auto amb-auto" style="color: #333;">
    <?php
// If $x and $y don't exist or aren't of the proper format, redirect the user:
if (isset($_GET['x'], $_GET['y'])&& filter_var($_GET['x'],FILTER_VALIDATE_EMAIL)&& (strlen($_GET['y']) == 32 )) {

// Update the database...
require (MYSQL);
$q = "UPDATE users SET active=NULL WHERE (email='".mysqli_real_escape_string($dbc, $_GET['x'])."'AND active='".mysqli_real_escape_string($dbc, $_GET['y'])."') LIMIT 1";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: ".mysqli_error($dbc));

// Print a customized message:
if (mysqli_affected_rows($dbc) == 1) {
echo "<h3>Your account is now active. You may now log in.</h3>";
} else {
echo '<h3 class="error">Your account could not be activated. Please
re-check the link or contact the system administrator.</h3>';
}

mysqli_close($dbc);

} else { // Redirect.

$url = BASE_URL.'index.php'; //Define the URL.
ob_end_clean( ); // Delete the buffer.
header("Location: $url");
exit( ); // Quit the script.
} // End of main IF-ELSE.
?>
</main>

<?php include 'includes/footer.html'; ?>
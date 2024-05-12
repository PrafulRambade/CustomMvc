<?php pageAdd('include/header.php'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6 pt-4">
            <h2>Login form</h2>
            <form id="loginForm" method="POST" action="submit-login">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                    <div id="emailError" class="text-danger"></div> <!-- Error message for email -->
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
                    <div id="passwordError" class="text-danger"></div> <!-- Error message for password -->
                </div>

                <!-- CSRF token field -->
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

                <button type="button" class="btn btn-primary" onclick="validateAndSubmit()">Submit</button>
                <a href="register" class="btn btn-dark">Register</a>
            </form>
        </div>
    </div>
</div>


<?php pageAdd('include/footer.php'); ?>

<?php
// We need to use sessions, so you should always initialize sessions using the below function
session_start();
// If the user is logged in, redirect to the home page
if (isset($_SESSION["account_loggedin"])) {
    header("Location: list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en-GB">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LOGIN | The Liam Forecast</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="The Liam Forecast" />
    <link rel="manifest" href="/site.webmanifest" />
    <link rel="stylesheet" href="/css/oasis.css" />
  </head>

  <body>
    <main class="oasis">
      <div class="login">
      
          <h1>Member Login</h1>
      
          <form action="auth.php" method="post" class="form login-form">
      
              <label class="form-label" for="username">Username</label>
              <div class="form-group">
                  <svg class="form-icon-left" width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
                  <input class="form-input" type="text" name="username" placeholder="Username" id="username" required>
              </div>
      
              <label class="form-label" for="password">Password</label>
              <div class="form-group mar-bot-5">
                  <svg class="form-icon-left" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/></svg>
                  <input class="form-input" type="password" name="password" placeholder="Password" id="password" required>
              </div>
      
              <button class="loginButton" type="submit">Login</button>
            
          </form>
      
      </div>
	</main>
  </body>
</html>

<?php
// client_id : 146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com

// echo 'google sign-in';
// include_once 'functions_utility.php';
$id_token = grabString('id_token');


// wut
// something about the order of the page load
// the $.post calls in the getTableData function break when i use this code
// i have no idea why; i suspect that this widget is getting "loaded" with every XMLHttpRequest and thus the meta tag etc are getting pushed back into the responseText propert of the XMLHttpRequest

// probably need to rethink this whole authentication workflow to use just one auth method and do it with some stand alone pages instead of trying to make it work as a widget

if (empty($id_token)){
  // do user interaction for google auth
  // echo '<meta name="google-signin-client_id" content="'. $a['client_id'] .'">';
  
  // echo '
  //   <script src="https://apis.google.com/js/platform.js" async defer></script>
  //   ';

  // echo '
  //   <script>
  //     console.log("google sigin javascript");

  //     function onSignIn(googleUser) {
  //       var profile = googleUser.getBasicProfile();
  //       console.log("ID: " + profile.getId()); // Do not send to your backend! Use an ID token instead.
  //       console.log("Name: " + profile.getName());
  //       console.log("Image URL: " + profile.getImageUrl());
  //       console.log("Email: " + profile.getEmail());

  //       var id_token = googleUser.getAuthResponse().id_token;
  //       console.log("ID token: " + id_token);

  //       xhr = new XMLHttpRequest();
  //       xhr.open("POST", "/lib/widget_google_signin.php");
  //       xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  //       xhr.onload = function() {
  //         console.log("Signed in as: " +xhr.responseText);
  //       }

  //       xhr.send("id_token=" + id_token);

  //     }
  //   </script>

  //   <div class="g-signin2" data-onsuccess="onSignIn"></div>
  // ';
} else {
  // chang the auth button so it says log me out
  // TODO server-side authenticat and then set username var;
  echo 'server-side id token = ' . $id_token;
}

?>

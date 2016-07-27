<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include 'shots/entities/people.php';



?>

<body>

  <form>
    User name:<br>
    <input type="text" name="username"><br>
    User password:<br>
    <input type="password" name="psw">

    <br>
    <input type="radio" name="gender" value="male" checked> Male<br>
    <input type="radio" name="gender" value="female"> Female<br>
    <input type="radio" name="gender" value="other"> Other

    <br>
    <input type="checkbox" name="vehicle1" value="Bike"> I have a bike<br>
    <input type="checkbox" name="vehicle2" value="Car"> I have a car<br>

    <br>
    <input type="button" name="generic-button" value="click me"/> 

    <br>
    <input type="number" name="quantity" min="1" max="5">

    <br>
    Select your favorite color: <input type="color" name="favcolor">

    <br>
    Birthday: <input type="date" name="bday">

    
    <br>
    Birthday (date and time): <input type="datetime-local" name="bdaytime">

    <br>
    E-mail: <input type="email" name="usremail">

    <br>
    Select a file: <input type="file" name="img">

    <br>
    Hidden: <input type="hidden" name="country" value="Norway">

    <br>
    Birthday (month and year): <input type="month" name="bdaymonth">

    <br>
    Quantity (between 1 and 5): <input type="number" name="quantity" min="1" max="5">

    <br>
    Range: <input type="range" name="points" min="0" max="10">

    <br>
    Search Google: <input type="search" name="googlesearch">

    <br>
    Telephone: <input type="tel" name="usrtel">

    <br>
    Select a time: <input type="time" name="usr_time">

    <br>
    Add your homepage: <input type="url" name="homepage">

    <br>
    Select a week: <input type="week" name="week_year">

    <br>
    <input type="submit" name="submit-button" value="Submit"> <input name="reset-button" type="reset">
  </form>
  
  <?php include 'html_footer.php'; ?>

  <script type="text/javascript">

    // this isn't done yet TODO finish it
    $(document).ready(function() {

      function foo(e) {
        e.preventDefault();
        console.log($(this));
        var my_input = getFormInputValue($(this));
        console.log(my_input);

      }

      $('input').change( foo );
      $('input[type=button]').click( foo );
      $('input[type=submit]').click( foo );
      
    });
  </script>

</body>
</html>



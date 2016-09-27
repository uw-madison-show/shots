<?php
require_once 'all_pages.php';

// stuff that goes at the top of every html page

?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo $app_root; ?>/">SHOTS</a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo $app_root;?>/">Home</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Jump To <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo $app_root; ?>/views/table_all_grants.php">Grants</a></li>
            <li><a href="<?php echo $app_root; ?>/views/table_all_people.php">People</a></li>
            <li><a href="<?php echo $app_root; ?>/views/manager_all_documents.php">Documents</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li class="dropdown-header">Calendars</li>
            <li><a href="<?php echo $app_root; ?>/views/calendar_month_all_events.php">Monthly</a></li>
            <li><a href="#">Today</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="navbar-authentication-button">
          <a href="<?php echo $app_root; ?>/lib/shots/internals/sessions.php?logout=true">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
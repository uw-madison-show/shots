<?php
include './lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$all_grants_sql = 'select * from grants';
$all_grants = $db->fetchAll($all_grants_sql);

?>

<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid" id="body-background">
  <div class="row page-content">
    <div class="col-xs-12 no-padding" role="main" id="region-main">
      <div class="row portal-frame">
        <div class="col-xs-12 portal-main">
          <ul class="tile-list" aria-invalid="false">
            <li class="col-xs-12 col-sm-6 col-md-4 no-padding list-container">
            
              <widget>
                <div class="widget-frame" id="portal-id-001">
                  <div class="widget-header">
                    <div class="widget-help">?</div>
                    <div class="widget-remove">x</div>
                    <div class="widget-title"><h4>Grants</h4></div>
                  </div>
                  <div class="widget-body">
                    <div class="quick-list-container">
                      <ul class="quick-list">
                        <?php
                          for ($i = 0; $i <= 3 ; $i++) { 
                            $g = $all_grants[$i];
                            echo '<li class="quick-list-item centering">';
                            echo '<a id="grants-' . $g['grant_id'] . '" href="/views/one_grants.php?id=' . $g['grant_id'] .'"">' . $g['title'] . '</a>';
                            echo '</li>';
                          }
                        ?>
                      </ul>
                    </div>
                  </div>
                  <a href="/views/table_all_grants.php" class="btn btn-default launch-app-button">All Grants</a>
                </div>
              </widget>
            </li>
            
            <li class="col-xs-12 col-sm-6 col-md-4 no-padding list-container">
              <widget>
                <div class="widget-frame" id="portal-id-002">
                  <div class="widget-header">
                    <div class="widget-help">?</div>
                    <div class="widget-remove">x</div>
                    <div class="widget-title"><h4>People</h4></div>
                  </div>
                  <div class="widget-body">
                    <a href="#" class="icon-link">
                      <div class="widget-icon-container">
                        <span class="fa fa-users"></span>
                      </div>
                    </a>
                    <button class="btn btn-default launch-app-button">All People</button>
                  </div>
                </div>
              </widget>
            </li>
              
            <li class="col-xs-12 col-sm-6 col-md-4 no-padding list-container">  
              <widget>
                <div class="widget-frame" id="portal-id-003">
                  <div class="widget-header">
                    <div class="widget-help">?</div>
                    <div class="widget-remove">x</div>
                    <div class="widget-title"><h4>Ancillary Studies</h4></div>
                  </div>
                  <div class="widget-body">
                    <div class="quick-list-container">
                      <ul class="quick-list">
                        <li class="quick-list-item centering">
                          <a id="studies-1" href="#">WARRIOR</a>
                        </li>
                        <li class="quick-list-item centering">
                          <a id="studies-2" href="#">Angler's II</a>
                        </li>
                        <li class="quick-list-item centering">
                          <a id="studies-3" href="#">Vitamin D</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <button class="btn btn-default launch-app-button">All Ancillary Studies</button>
                </div>
              </widget>
            </li>
              
            <li class="col-xs-12 col-sm-6 col-md-4 no-padding list-container">  
              <widget>
                <div class="widget-frame" id="portal-id-001">
                  <div class="widget-header">
                    <div class="widget-help">?</div>
                    <div class="widget-remove">x</div>
                    <div class="widget-title"><h4>Events</h4></div>
                  </div>
                  <div class="widget-body">
                    <div class="col-xs-6 centering">
                      <a href="#" class="icon-link">
                        <div class="widget-multiple-button">
                          <span class="fa fa-calendar"></span>
                        </div>
                        <p>Calender</p>
                      </a>
                    </div>
                    <div class="col-xs-6 centering">
                      <a href="#" class="icon-link">
                        <div class="widget-multiple-button">
                          <span class="fa fa-calendar-plus-o"></span>
                        </div>
                        <p>Add Meeting</p>
                      </a>
                    </div>
                    <div class="col-xs-6 centering">
                      <a href="#" class="icon-link">
                        <div class="widget-multiple-button">
                          <span class="fa fa-list"></span>
                        </div>
                        <p>Upcoming Deadlines</p>
                      </a>
                    </div>
                  </div>  
                </div>
              </widget>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">
  // page specific js goes here
</script>
</body>
</html>
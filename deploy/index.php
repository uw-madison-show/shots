<?php
include './lib/all_pages.php';
include_once 'functions_database.php';

// test if the database exists, if not, go to the db manager page
$first_load = $db->getSchemaManager();
$db_exists = sqliteDatabaseExists($first_load);
if (!$db_exists){
  header('Location: '. $app_root . '/manage_database.php');
  exit();
}

include 'html_doctype.php';
include 'html_head.php';

include_once 'shots/entities/grants.php';
include_once 'shots/entities/people.php';
include_once 'shots/entities/documents.php';


$recent_grants = encode(grantsFetchRecent(7));
$recent_people = encode(peopleFetchRecent(7));
$recent_documents = encode(documentsFetchRecent(7));




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
                          foreach ($recent_grants as $id => $data) { 
                            if ( !empty($data) ){                         
                              echo '<li class="quick-list-item align-left">';
                              echo '<a id="grants-' . $data['grant_id'] . '" href="'. $app_root .'/views/one_grants.php?id=' . $data['grant_id'] .'"">';
                              echo  ($data['title'] ? $data['title'] : 'no title');
                              echo '</a>';
                              echo ($data['grant_body'] ? '<span class="smaller"> ('. $data['grant_body'] .')</span>' : '');
                              echo '</li>';
                            }
                          }
                        ?>
                      </ul>
                    </div>
                  </div>
                  <a href="<?php echo $app_root; ?>/views/table_all_grants.php" class="btn btn-default launch-app-button">All Grants</a>
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
                    <div class="quick-list-container">
                      <ul class="quick-list">
                        <?php
                          foreach ($recent_people as $id => $data) { 
                            if ( !empty($data) ){                         
                              echo '<li class="quick-list-item align-left">';
                              echo '<a id="grants-' . $data['person_id'] . '" href="'. $app_root .'/views/one_people.php?id=' . $data['person_id'] .'"">';
                              echo  ($data['name'] ? $data['name'] : 'no name');
                              echo '</a>';
                              echo ($data['affiliation'] ? '<span class="smaller"> ('. $data['affiliation'] .')</span>' : '');
                              echo '</li>';
                            }
                          }
                        ?>
                      </ul>
                    </div>
                  </div>
                  <a href="<?php echo $app_root; ?>/views/table_all_people.php" class="btn btn-default launch-app-button">All People</a>
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
                    <p>Placeholder. Not real links.</p>
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

            <li class="col-xs-12 col-sm-6 col-md-4 no-padding list-container">
              <widget>
                <div class="widget-frame" id="portal-id-001">
                  <div class="widget-header">
                    <div class="widget-help">?</div>
                    <div class="widget-remove">x</div>
                    <div class="widget-title"><h4>Documents</h4></div>
                  </div>
                  <div class="widget-body">
                    <div class="quick-list-container">
                      <ul class="quick-list">
                        <?php
                          foreach ($recent_documents as $id => $data) { 
                            if ( !empty($data) ){                         
                              echo '<li class="quick-list-item align-left">';
                              echo '<a id="grants-' . $data['document_id'] . '" href="'. $app_root .'/views/one_documents.php?id=' . $data['document_id'] .'"">';
                              echo  ($data['name'] ? $data['name'] : 'unnamed');
                              echo '</a>';
                              echo ($data['extension'] ? '<span class="smaller"> ('. $data['extension'] .')</span>' : '');
                              echo '</li>';
                            }
                          }
                        ?>
                      </ul>
                    </div>
                  </div>
                  <a href="<?php echo $app_root; ?>/views/manager_all_documents.php" class="btn btn-default launch-app-button">All Documents</a>
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
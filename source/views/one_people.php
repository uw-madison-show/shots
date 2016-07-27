<?php
include '../lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'people';
$this_id    = grabString('id');

include 'shots/entities/people.php';

$results = peopleFetch( array($this_id) );

$all_html = '';

foreach ($results as $people_id => $data) {
  foreach ($data as $key => $value) {
    $html = peopleCreateFieldHtml($key, $value);
    $all_html .= $html;
  }
}

?>

<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div id="main-entity" class="col-md-8">
      <div class="form-horizontal">
        <div class="record" data-entity-name="people">
          <div class="fields">
          <?php echo $all_html; ?>
          </div>
        </div>
      </div>
    </div>
    <div id="related-entities" class="col-md-3">
      related things
    </div>
  </div>
</div>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">
  $(document).ready(function() {
    console.log('ready');

    $('input').change( ajaxChange );
  }); // end document ready
</script>

</body>
</html>
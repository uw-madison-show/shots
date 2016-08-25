<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

include 'shots/entities/documents.php';

// $foo = grabString('param');

// $bar = documentsAdd('title', 'blah');

// $foo = documentsSearch('server_name', 'f238640938d3c6cc9e0e89b1e78afef9.png', 'native');

// $baz = $foo[0]['document_id'];

// $ck_n = documentsUpdate($baz, 'name', 'diff');

$foo = documentsFetchAll('native');

// error_log('test error message');

// $obfus_name = md5('hello') . '.' . 'xlsx';

// look for exact matching name and extension
// $q = $db->prepare('select count(*) from documents where server_name = :name');
// $q->bindValue('name', $obfus_name);
// $q->execute();
// $r = $q->fetchAll()[0]['count(*)'];
// print_r($r);

// $search_value = array('inside-r.png',
//                       'todo_list_for_after_vacation_20160603.txt',
//                       'foo',
//                       'bar',
//                       7,
//                       );

// $q = $db->createQueryBuilder();
// $q->select('*');
// $q->from('documents');
// $q->where('name in (?)');
// $sql = $q->getSQL();


// $s = $db->executeQuery($sql,
//                        array($search_value),
//                        array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
//                        );

// $r = $s->fetchAll();





?>

<body>

  <?php include 'html_navbar.php'; ?>

  <form id="fileupload-form">

    <div class="form-group">
      <label for="title">Title:</label>
      <input class="form-control" type="text" id="title" name="title"/>
    </div>

    <div class="form-group">
      <label for="description">Description:</label>
      <input class="form-control" type="text" id="description" name="description"/>
    </div>

    <input id="my_upload" type="file" name="files[]" data-url="lib/file_handler.php" />
  </form>

  <?php include 'html_footer.php'; ?>

  <script type="text/javascript">
  $(document).ready(function() {
    
    /**********************************************************/

    // Page Setup Code

    /**********************************************************/
    $('#my_upload').fileupload({
      dataType: 'json',
      done: function (e, data) {
        console.log(e);
        console.log(data);
      }
    });
    
    /**********************************************************/

    // Event Listeners

    /**********************************************************/

  });
  </script>

</body>
</html>



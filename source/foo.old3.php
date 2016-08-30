<?php
include './lib/all_pages.php';

// ob_start();
include 'html_doctype.php';
include 'html_head.php';
// ob_end_flush();

$new_rel_start = microtime(true);
include 'shots/relationships/relationships.php';
$new_rel = relationshipsAdd('grants',
                            '2',
                            'people',
                            '4'
                            );
$new_rel_done = microtime(true);

echo '<div>relationshipsAdd ' . ($new_rel_done - $new_rel_start) . '</div>';

$foo = $db->query('select * from relationships')->fetchAll();

?>

<body>

<?php include 'html_navbar.php'; ?>

<?php include 'html_footer.php'; ?>

<script>
  
</script>
</body>
</html>
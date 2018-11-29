<?php
require_once('../includes/initialize.php');
?>

<?php
// THE CURRENT PAGE NUMBER ($CURRENT_PAGE)
  $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

  $per_page = 1;

  $total_record = Photograph::count_all();

 ?>

<?php
include_layout_template('header.php');


// //  Using pagination instead
//$photos = Photograph::find_all();

$pagination =  new Pagination($page, $per_page, $total_record);

$sql = " SELECT * FROM photographs ";
$sql .= " LIMIT {$per_page} ";
$sql .= " OFFSET {$pagination->offset()}";


 $photos = Photograph::find_by_sql($sql);

 foreach($photos as $photo) {
   echo "<br />";
  ?>
  <a href="photo.php?id=<?php echo $photo->id ?>">
  <img src="<?php echo $photo->image_path(); ?>" alt="" caption="<?php echo $photo->caption; ?>" width="300" />
  </a>
  <p><?php echo $photo->filename; ?></p>
  <p><?php echo $photo->caption; ?></p>
  <p><?php echo $photo->size_as_text(); ?></p>
  <p><?php echo $photo->type; ?></p>

<?php   }

if($pagination->total_pages() > 1){
  if($pagination->has_next_page()) {
    echo "<a href=\"index.php?page=";
    echo $pagination->next_page();
    echo "\">Next &raquo;</a>";
  }

  for($i=1; $i<= $pagination->total_pages(); $i++){
  echo "<a href=\"index.php?page{$i}\"> ${i} </a>";
  }


  if($pagination->has_previous_page()) {
    echo "<a href=\"index.php?page=";
    echo $pagination->previous_page();
    echo "\"> Previous </a>";
  }
}

include_layout_template('footer.php');
?>

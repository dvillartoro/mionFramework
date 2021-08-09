<h1><?php echo $title ?></h1>
<ul>
    <li>Easy</li>
    <li>Flexible</li>
    <li>Light</li>
    <li>Customizable</li>
</ul>
<?php echo $more_text ?>
<?php
foreach($tasks as $task){
    echo '<hr>'.$task;
}
?>
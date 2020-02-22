<?php
require_once('core/Base.php');
require_once('config/config.php');

require_once('models/model_admin.php');
require_once('models/model_student.php');
require_once('models/model_teacher.php');

require_once('views/view_admin.php');
require_once('views/view_student.php');
require_once('views/view_teacher.php');

$model_Student = new Model_Student();
$model_Admin = new Model_Admin();
$model_Teacher = new Model_Teacher();



public function get_my_messenger()
{
	$messenger = array();
	$recent_user = $model_Admin->get_recent_messenger_user("nguyenvancaoky");
	return $recent_user;
}

print_r(get_my_messenger());



?>

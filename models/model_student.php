<?php

require_once('config/database.php');

class Model_Student extends Database
{
	public function get_list_document()
	{
			$sql = "
			SELECT DISTINCT * FROM document
			";
			$this->set_query($sql);
			return $this->load_rows();
	}
	public function get_list_subjects()
	{
			$sql = "SELECT DISTINCT * FROM subjects";

			$this->set_query($sql);
			return $this->load_rows();
	}
	public function get_recent_messenger_user($username)
	{
			$sql = "
			SELECT username_send AS username, COUNT(username_send) AS count FROM messenger WHERE username_get = :username GROUP BY username_send HAVING COUNT(username_send) > 0 ORDER BY time DESC
			";
			$param = [ ':username' => $username ];

			$this->set_query($sql, $param);
			return $this->load_rows();
	}
	public function get_info_messenger_user($username)
	{
			$sql = "
			SELECT DISTINCT username, name, permission, avatar FROM students WHERE username = :username OR email = :username UNION SELECT DISTINCT username, name, permission, avatar FROM teachers WHERE username = :username OR email = :username UNION SELECT DISTINCT username, name, permission, avatar FROM admins WHERE username = :username OR email = :username
			";
			$param = [ ':username' => $username ];

			$this->set_query($sql, $param);
			return $this->load_row();
	}
	public function get_user_messenger($username_send,$username_get)
	{
			$sql = "
			SELECT DISTINCT id, content, time, username_get, username_send FROM messenger WHERE (username_send = :username_send AND username_get = :username_get) OR (username_send = :username_get AND username_get = :username_send) ORDER BY time ASC
			";
			$param = [ ':username_send' => $username_send, ':username_get' => $username_get ];

			$this->set_query($sql, $param);
			return $this->load_rows();
	}
	public function get_new_messenger($username_send,$username_get,$count)
	{
			$sql = "
			SELECT DISTINCT id, content, TIME, username_get, username_send FROM messenger WHERE username_send = '$username_send' AND username_get = '$username_get' ORDER BY TIME DESC LIMIT $count
			";
			$this->set_query($sql);
			return $this->load_rows();
	}
	public function send_messenger($username_get,$username_send,$content)
	{
			$sql = "
			INSERT INTO messenger (id, username_send, username_get, content, time) VALUES (NULL, :username_send, :username_get, :content, current_timestamp());
			";

			$param = [ ':username_get' => $username_get, ':username_send' => $username_send, ':content' => $content ];

			$this->set_query($sql, $param);
			return $this->execute_return_status();
	}
	public function update_messenger_seen($send_get)
	{
			$sql = "
			INSERT INTO messenger_seen( send_get, count ) VALUES( :send_get, count +1 ) ON DUPLICATE KEY UPDATE count = count +1
			";

			$param = [ ':send_get' => $send_get ];

			$this->set_query($sql, $param);
			return $this->execute_return_status();
	}
	public function clear_messenger_seen($send_get)
	{
			$sql = "
			INSERT INTO messenger_seen( send_get, count ) VALUES( :send_get, 0 ) ON DUPLICATE KEY UPDATE count = 0
			";

			$param = [ ':send_get' => $send_get ];

			$this->set_query($sql, $param);
			return $this->execute_return_status();
	}
	public function get_count_messenger_seen($username)
	{
		$sql = "
		SELECT * FROM messenger_seen WHERE send_get LIKE '%$username'
		";
		$this->set_query($sql);
		return $this->load_rows();
	}
	public function get_count_messenger_seen_user($username_send,$username_get)
	{
		$sql = "
		SELECT * FROM messenger_seen WHERE send_get LIKE '%$username_get' AND send_get LIKE '$username_send%'
		";
		$this->set_query($sql);
		return $this->load_row();
	}
	public function get_diem_so($student_id)
	{
			$sql = "SELECT DISTINCT scores.test_code, tests.test_name, subjects.subject_detail, tests.grade_id, scores.score_number,scores.score_detail,scores.completion_time FROM scores
			INNER JOIN tests ON scores.test_code = tests.test_code
			INNER JOIN subjects ON tests.subject_id = subjects.subject_id
			WHERE student_id = :student_id";

			$param = [ ':student_id' => $student_id ];

			$this->set_query($sql, $param);
			return $this->load_rows();
	}
	public function get_list_quest()
	{
			$sql = "
			SELECT DISTINCT * FROM questions
			";
			$this->set_query($sql);
			return $this->load_rows();
	}
	public function get_list_students()
	{
			$sql = "
			SELECT DISTINCT * FROM students
			";
			$this->set_query($sql);
			return $this->load_rows();
	}
	public function get_list_tests_code()
	{
			$sql = "
			SELECT DISTINCT * FROM tests
			";
			$this->set_query($sql);
			return $this->load_rows();
	}
	public function get_rankings()
	{
		$sql = "SELECT DISTINCT ranking.rank_id, ranking.EXP, ranking.exp_remaining, rank.rank_name, students.name, classes.class_name FROM `ranking` INNER JOIN rank ON ranking.rank_id = rank.rank_id INNER JOIN students ON students.student_id = ranking.student_id INNER JOIN classes ON students.class_id = classes.class_id ORDER BY ranking.EXP DESC";

		$this->set_query($sql);
		return $this->load_rows();
	}
	public function delete_scores($student_id,$test_code)
	{
		$sql="DELETE FROM scores WHERE student_id = :student_id AND test_code = :test_code";

		$param = [ ':student_id' => $student_id, ':test_code' => $test_code ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function insert_score_ao($question_id,$count,$total,$a,$b,$c,$d,$blank)
	{
		$sql = "INSERT INTO quest_incorrect_rank (question_id, count, total, a, b, c, d, blank) VALUES (:question_id, :count, :total, :a, :b, :c, :d, :blank)";

				$param = [ ':question_id' => $question_id, ':count' => $count, ':total' => $total, ':a' => $a, ':b' => $b, ':c' => $c, ':d' => $d, ':blank' => $blank ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function submit_feedback($feedback,$student_id)
	{
		$sql = "INSERT INTO feedback (student_id, content) VALUES (:student_id, :feedback)";

				$param = [ ':student_id' => $student_id, ':feedback' => $feedback ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function delete_test_detail($student_id,$test_code)
	{
		$sql="DELETE FROM student_test_detail WHERE student_id = :student_id AND test_code = :test_code";

		$param = [ ':student_id' => $student_id, ':test_code' => $test_code ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function get_profiles($username)
	{
		$sql = "SELECT DISTINCT students.student_id as ID,students.username,students.name,students.email,students.avatar,students.class_id,students.birthday,students.last_login,genders.gender_id,genders.gender_detail,classes.grade_id,students.doing_exam,students.time_remaining FROM `students`
		INNER JOIN genders ON genders.gender_id = students.gender_id
		INNER JOIN classes ON classes.class_id = students.class_id
		WHERE username = :username";

        $param = [ ':username' => $username ];

		$this->set_query($sql, $param);
		return $this->load_row();
	}
	public function create_ranking($student_id)
	{
		$sql = "INSERT INTO ranking (student_id,rank_id,exp,exp_remaining) VALUES (:student_id, '1', '0', '100')";

				$param = [ ':student_id' => $student_id ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function get_ranking($student_id)
	{
		$sql = "SELECT DISTINCT ranking.rank_id, ranking.EXP, ranking.exp_remaining, rank.rank_name, rank.rank_exp FROM `ranking` INNER JOIN rank ON ranking.rank_id = rank.rank_id WHERE student_id = :student_id ";

				$param = [ ':student_id' => $student_id ];

		$this->set_query($sql, $param);
		return $this->load_row();
	}
	public function get_ranking_index()
	{
		$sql = "SELECT student_id FROM `ranking` ORDER BY `ranking`.`exp` DESC";

		$this->set_query($sql);
		return $this->load_rows();
	}
	public function update_rank_point($exp,$rank_id,$exp_remaining,$student_id)
	{
		$sql="UPDATE ranking SET exp = :exp, rank_id = :rank_id, exp_remaining = :exp_remaining WHERE student_id = :student_id";

				$param = [ ':exp' => $exp, ':rank_id' => $rank_id, ':exp_remaining' => $exp_remaining, ':student_id' => $student_id ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function get_score($student_id,$test_code)
	{
		$sql = "SELECT DISTINCT * FROM `scores` WHERE student_id = :student_id AND test_code = :test_code";

        $param = [ ':student_id' => $student_id, ':test_code' => $test_code ];

		$this->set_query($sql, $param);
		return $this->load_row();
	}

	public function get_scores($student_id)
	{
		$sql = "SELECT DISTINCT * FROM `scores` WHERE student_id = :student_id";

        $param = [ ':student_id' => $student_id ];

		$this->set_query($sql, $param);
		return $this->load_rows();
	}

	public function get_notifications($class_id)
	{
		$sql = "SELECT DISTINCT * FROM notifications WHERE notification_id IN (SELECT DISTINCT notification_id FROM student_notifications WHERE class_id = :class_id) ORDER BY `time_sent` DESC";

        $param = [ ':class_id' => $class_id ];

		$this->set_query($sql, $param);
		return $this->load_rows();
	}

	public function get_chats($class_id)
	{
		$sql = "SELECT DISTINCT * FROM `chats` WHERE class_id = :class_id ORDER BY ID DESC LIMIT 10";

        $param = [ ':class_id' => $class_id ];

		$this->set_query($sql, $param);
		return $this->load_rows();
	}

	public function get_chat_all($class_id)
	{
		$sql = "SELECT DISTINCT * FROM `chats` WHERE class_id = :class_id ORDER BY ID DESC";

        $param = [ ':class_id' => $class_id ];

		$this->set_query($sql, $param);
		return $this->load_rows();
	}

	public function chat($username, $name, $class_id, $content)
	{
		$sql = "INSERT INTO chats (username,name,class_id,chat_content,time_sent) VALUES (:username,:name,:class_id,:content,NOW())";

        $param = [ ':username' => $username, ':name' => $name, ':class_id' => $class_id, ':content' => $content ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}

	public function update_last_login($ID)
	{
		$sql="UPDATE students set last_login = NOW() where student_id = :ID";

        $param = [ ':ID' => $ID ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}

	public function get_sub_time($username)
	{
		$sql = "SELECT DISTINCT starting_time,time_to_do FROM `students` INNER JOIN tests ON students.doing_exam = tests.test_code WHERE username = :username";

				$param = [ ':username' => $username ];

		$this->set_query($sql, $param);
		return $this->load_row();
	}

	public function get_time_out($get_sub_time)
	{
		$time_to_do = (($get_sub_time->time_to_do)*60);
		$starting_time = strtotime($get_sub_time->starting_time);
		$curren_time = strtotime(date("Y-m-d H:i:s"));
		$sub_time = ($curren_time - $starting_time) + 30; // cho phép sai số phạm vi 30s
		$time_out = ($sub_time > $time_to_do) ? 1 : 0;
		return $time_out;
	}
	public function get_sec_time($get_sub_time)
	{
		$time_to_do = (($get_sub_time->time_to_do)*60);
		$starting_time = strtotime($get_sub_time->starting_time);
		$curren_time = strtotime(date("Y-m-d H:i:s"));
		$sub_time = ($curren_time - $starting_time);
		$left_time = ($time_to_do - $sub_time);
		return $left_time;
	}

	public function update_doing_exam($test_code,$time,$ID)
	{
		$sql="UPDATE students set doing_exam = :test_code, time_remaining = :time, starting_time = NOW() where student_id = :ID";

        $param = [ ':test_code' => $test_code, ':time' => $time, ':ID' => $ID ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}

	public function reset_doing_exam($ID)
	{
		$sql="UPDATE students set doing_exam = NULL, time_remaining = NULL, starting_time = NULL where student_id = :ID";

        $param = [ ':ID' => $ID ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}

	public function valid_email_on_profiles($curren_email, $new_email)
	{
		$sql = "SELECT DISTINCT name FROM teachers WHERE email = :new_email AND email NOT IN (:curren_email)
		UNION SELECT DISTINCT name FROM admins WHERE email = :new_email AND email NOT IN (:curren_email)
		UNION SELECT DISTINCT name FROM students WHERE email = :new_email AND email NOT IN (:curren_email)";

        $param = [ ':new_email' => $new_email, ':curren_email' => $curren_email ];

		$this->set_query($sql, $param);
		if ($this->load_row() != '') {
			return false;
		} else {
			return true;
		}
	}

	public function update_avatar($avatar, $username)
	{
		$sql="UPDATE students set avatar = :avatar where username = :username";

        $param = [ ':avatar' => $avatar, ':username' => $username ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}

	public function update_profiles($username, $name, $email, $password, $gender, $birthday)
	{
		$sql="UPDATE students set email = :email, password = :password, name = :name, gender_id = :gender, birthday = :birthday where username = :username";

        $param = [ ':username' => $username, ':name' => $name, ':email' => $email, ':password' => $password, ':gender' => $gender, ':birthday' => $birthday ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
		return true;
	}

	public function get_list_tests()
	{
		$sql = "
		SELECT DISTINCT tests.test_code,tests.test_name,tests.password,tests.total_questions,tests.time_to_do,tests.note,grades.detail as grade,subjects.subject_detail,subjects.subject_id,statuses.status_id,statuses.detail as status FROM `tests`
		INNER JOIN grades ON grades.grade_id = tests.grade_id
		INNER JOIN subjects ON subjects.subject_id = tests.subject_id
		INNER JOIN statuses ON statuses.status_id = tests.status_id
		WHERE test_type=1
		ORDER BY timest DESC

		";

		$this->set_query($sql);
		return $this->load_rows();
	}
	public function get_list_courses()
	{
			$sql = "
			SELECT DISTINCT tests.test_code,tests.test_name,tests.test_type,tests.password,tests.total_questions,tests.time_to_do,tests.note,grades.detail as grade,subjects.subject_detail,subjects.subject_id,statuses.status_id,statuses.detail as status FROM `tests`
			INNER JOIN grades ON grades.grade_id = tests.grade_id
			INNER JOIN subjects ON subjects.subject_id = tests.subject_id
			INNER JOIN statuses ON statuses.status_id = tests.status_id
			WHERE test_type=2
			ORDER BY timest DESC
			";

			$this->set_query($sql);
			return $this->load_rows();
	}

	public function get_test($test_code)
	{
		$sql = "SELECT DISTINCT * FROM tests WHERE test_code = :test_code";

        $param = [ ':test_code' => $test_code ];

		$this->set_query($sql, $param);
		return $this->load_row();
	}
	public function get_quest_of_test($test_code)
	{
		$sql = "SELECT DISTINCT * FROM quest_of_test
		INNER JOIN questions ON questions.question_id = quest_of_test.question_id
		WHERE test_code = :test_code ORDER BY RAND()";

        $param = [ ':test_code' => $test_code ];

		$this->set_query($sql, $param);
		return $this->load_rows();
	}

	public function add_student_quest($student_id, $ID, $test_code, $question_id, $answer_a, $answer_b, $answer_c, $answer_d)
	{
		$sql = "INSERT INTO `student_test_detail` (`student_id`,`ID`,`test_code`, `question_id`, `answer_a`, `answer_b`, `answer_c`, `answer_d`) VALUES (:student_id, :ID, :test_code, :question_id, :answer_a, :answer_b, :answer_c, :answer_d);";

        $param = [ ':student_id' => $student_id, ':ID' => $ID, ':test_code' => $test_code, ':question_id' => $question_id, ':answer_a' => $answer_a, ':answer_b' => $answer_b, ':answer_c' => $answer_c, ':answer_d' => $answer_d ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function get_type_quest($test_code)
	{
		$sql = "SELECT DISTINCT	tests.test_type FROM tests
		WHERE test_code = :test_code";

        $param = [ ':test_code' => $test_code ];

		$this->set_query($sql, $param);
		return $this->load_row();
	}
	public function get_doing_quest($test_code,$student_id)
	{
		$sql = "SELECT DISTINCT student_test_detail.answer_a,student_test_detail.answer_b,student_test_detail.answer_c,student_test_detail.answer_d,student_test_detail.student_answer,questions.question_id,student_test_detail.test_code,questions.question_content,questions.huong_dan FROM student_test_detail
		INNER JOIN questions ON student_test_detail.question_id = questions.question_id
		WHERE test_code = :test_code AND student_id = :student_id ORDER BY ID";

        $param = [ ':test_code' => $test_code, ':student_id' => $student_id ];

		$this->set_query($sql, $param);
		return $this->load_rows();
	}

	public function get_result_quest($test_code,$student_id)
	{
		$sql = "SELECT DISTINCT student_test_detail.answer_a,student_test_detail.answer_b,student_test_detail.answer_c,student_test_detail.answer_d,student_test_detail.student_answer,questions.question_id,student_test_detail.test_code,questions.question_content,questions.correct_answer,tests.total_questions,questions.huong_dan,tests.test_type FROM student_test_detail
		INNER JOIN questions ON student_test_detail.question_id = questions.question_id
		INNER JOIN tests ON student_test_detail.test_code = tests.test_code
		WHERE student_test_detail.test_code = :test_code AND student_id = :student_id ORDER BY ID";

        $param = [ ':test_code' => $test_code, ':student_id' => $student_id ];

		$this->set_query($sql, $param);
		return $this->load_rows();
	}
	public function update_quest_incorrect_rank($question_id,$answer)
	{
		$sql='INSERT INTO quest_incorrect_rank (question_id, count, total, '.$answer.') VALUES(:question_id, count+1, total+1, '.$answer.'+1) ON DUPLICATE KEY UPDATE count = count +1, total = total +1, '.$answer.' = '.$answer.'+1';
        $param = [ ':question_id' => $question_id ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function update_quest_correct_rank($question_id,$answer)
	{
		$sql='INSERT INTO quest_incorrect_rank (question_id, total, '.$answer.') VALUES(:question_id, total+1, '.$answer.'+1) ON DUPLICATE KEY UPDATE total = total +1, '.$answer.' = '.$answer.'+1';

				$param = [ ':question_id' => $question_id ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}

	public function update_answer($student_id, $test_code, $question_id,$student_answer)
	{
		$sql="UPDATE student_test_detail set student_answer = :student_answer where student_id = :student_id AND test_code = :test_code AND question_id = :question_id";

        $param = [ ':student_id' => $student_id, ':test_code' => $test_code, ':question_id' => $question_id, ':student_answer' => $student_answer ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}

    public function get_student_quest_of_testcode($student_id, $test_code, $question_id)
    {
        $sql="SELECT * FROM student_test_detail where student_id = :student_id AND test_code = :test_code AND question_id = :question_id";

        $param = [ ':student_id' => $student_id, ':test_code' => $test_code, ':question_id' => $question_id ];

        $this->set_query($sql, $param);
        return $this->load_row();
    }

	public function update_timing($student_id, $time)
	{
		$sql="UPDATE students set time_remaining = :time where student_id = :student_id";

        $param = [ ':student_id' => $student_id, ':time' => $time ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}

	public function insert_score($student_id,$test_code,$score,$score_detail)
	{
		$sql = "INSERT INTO `scores` (`student_id`, `test_code`, `score_number`, `score_detail`, completion_time) VALUES (:student_id, :test_code, :score, :score_detail, NOW())";

        $param = [ ':student_id' => $student_id, ':test_code' => $test_code, ':score' => $score, ':score_detail' => $score_detail ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
	public function insert_score_ao_2($student_id,$test_code,$score,$score_detail,$completion_time)
	{
		$sql = "INSERT INTO `scores` (`student_id`, `test_code`, `score_number`, `score_detail`, `completion_time`) VALUES (:student_id, :test_code, :score, :score_detail, :completion_time)";

				$param = [ ':student_id' => $student_id, ':test_code' => $test_code, ':score' => $score, ':score_detail' => $score_detail, ':completion_time' => $completion_time ];

		$this->set_query($sql, $param);
		return $this->execute_return_status();
	}
}

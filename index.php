<?php
require_once('../../config.php');
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/users_report/index.php');
$PAGE->set_title('Enrollment Report');
$PAGE->set_heading('Enrollment Report');
echo $OUTPUT->header();

// Ensure only administrators can access the report
if (!is_siteadmin()) {
    echo $OUTPUT->notification('This page is available for administrators only.');
    echo $OUTPUT->footer();
    die();
}
?>

<?php
// Fetch random users and their enrolled courses
$sql = "
    SELECT u.id AS userid, u.firstname, u.lastname, GROUP_CONCAT(c.shortname SEPARATOR ', ') AS courses
    FROM {user} u
    JOIN {user_enrolments} ue ON u.id = ue.userid
    JOIN {enrol} e ON ue.enrolid = e.id
    JOIN {course} c ON e.courseid = c.id
    GROUP BY u.id
    ORDER BY u.id, c.id
    LIMIT 10
";

$user_courses = $DB->get_records_sql($sql);

// Display user enrollments
echo '<table class="generaltable">';
echo '<tr><th>User</th><th>Enrolled Courses</th></tr>';
foreach ($user_courses as $record) {
    // New user, start a new row
    echo '<tr>';
    echo '<td>' . fullname($record) . '</td>';
    echo '<td>' . $record->courses . '</td>';
}
echo '</table>';

echo $OUTPUT->footer();

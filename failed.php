<?php

require_once(__DIR__ . '/../../config.php');

$contextid = required_param('contextid', PARAM_INT);

$url = new moodle_url('/local/webmonetization/failed.php', ['contextid' => $contextid]);
$PAGE->set_url($url);
$context = context::instance_by_id($contextid);
$PAGE->set_context($context);

echo $OUTPUT->header();
echo $OUTPUT->notification(get_string('nonmonetizedbrowser', 'local_webmonetization'), \core\output\notification::NOTIFY_ERROR);
echo $OUTPUT->footer();

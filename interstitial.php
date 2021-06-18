<?php

use local_webmonetization\contextpaymentpointer;
use local_webmonetization\receiptverifier;

require_once(__DIR__ . '/../../config.php');

$contextid = required_param('contextid', PARAM_INT);

$url = new moodle_url('/local/webmonetization/failed.php', ['contextid' => $contextid]);
$PAGE->set_url($url);
$context = context::instance_by_id($contextid);
$PAGE->set_context($context);
$PAGE->set_course(get_course($context->get_course_context()->instanceid));

echo $OUTPUT->header();
echo html_writer::tag('h1', 'TRYING');
echo $OUTPUT->footer();

<?php

use local_webmonetization\contextpaymentpointer;
use local_webmonetization\receiptverifier;

require_once(__DIR__ . '/../../config.php');

$contextid = required_param('contextid', PARAM_INT);

$url = new moodle_url('/local/webmonetization/interstitial.php', ['contextid' => $contextid]);
$PAGE->set_url($url);
$context = context::instance_by_id($contextid);
$PAGE->set_context($context);

if (in_array($context->contextlevel, [CONTEXT_MODULE, CONTEXT_COURSE])) {
    $PAGE->set_course(get_course($context->get_course_context()->instanceid));
}

echo $OUTPUT->header();
echo html_writer::tag('h1', get_string('checkingbrowser', 'local_webmonetization'));
echo $OUTPUT->footer();

<?php

use local_webmonetization\contextpaymentpointer;
use local_webmonetization\receiptverifier;

require_once(__DIR__ . '/../../config.php');

$contextid = required_param('contextid', PARAM_INT);

$url = new moodle_url('/local/webmonetization/failed.php', ['contextid' => $contextid]);
$PAGE->set_url($url);
$PAGE->set_context(context::instance_by_id($contextid));


echo $OUTPUT->header();
echo html_writer::tag('h1', 'TRYING');
echo $OUTPUT->footer();

<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package local_webmonetization
 * @author Andrew Hancox <andrewdchancox@googlemail.com>
 * @author Open Source Learning <enquiries@opensourcelearning.co.uk>
 * @link https://opensourcelearning.co.uk
 * @link https://webmonetization.org
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2021, Andrew Hancox
 */

use local_webmonetization\contextpaymentpointer;

require_once(__DIR__ . '/../../config.php');

$contextid = required_param('contextid', PARAM_INT);
$context = context::instance_by_id($contextid);

require_capability('local/webmonetization:managepaymentpointers', $context);

$pageurl = new moodle_url('/local/webmonetization/editcontextpaymentpointer.php', ['contextid' => $contextid]);

$PAGE->set_url($pageurl);
$PAGE->set_context($context);

switch ($context->contextlevel) {
    case CONTEXT_COURSE:
        $PAGE->set_pagelayout('incourse');
        $PAGE->set_course(get_course($context->instanceid));
        break;
    default:
        $PAGE->set_pagelayout('admin');
}

$pagetitle = get_string('managepaymentpointerincontext', 'local_webmonetization',
        ['contextname' => $context->get_context_name(true)]);
$PAGE->set_title($pagetitle);

$contextpaymentpointer = contextpaymentpointer::get_record(['contextid' => $contextid]);
if (!$contextpaymentpointer) {
    $contextpaymentpointer = new contextpaymentpointer(0, (object)['contextid' => $contextid]);
}

$form = new \local_webmonetization\contextpaymentpointerform(
        $pageurl->out(false),
        ['persistent' => $contextpaymentpointer]
);

if ($formdata = $form->get_data()) {
    if (empty($formdata->id)) {
        $persistent = new contextpaymentpointer();
        $persistent->from_record($formdata);
        $persistent->create();
    } else {
        $persistent = $this->get_persistent();
        $persistent->from_record($formdata);
        $persistent->update();
    }

    redirect($context->get_url());
} else if ($form->is_cancelled()) {
    redirect($context->get_url());
}

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);

echo $form->render();

echo $OUTPUT->footer();

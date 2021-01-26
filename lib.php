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

defined('MOODLE_INTERNAL') || die();

function local_webmonetization_before_standard_html_head() {
    global $PAGE;
    $paymentpointer = contextpaymentpointer::nearest_paymentpointer($PAGE->context);

    if (!$paymentpointer) {
        return '';
    }

    $output = '<meta name="monetization" content="' . $paymentpointer . '">';
    return $output;
}

/**
 * Display the editcontextpaymentpointer link in the course administration menu.
 *
 * @param settings_navigation $parentnode The settings navigation object
 * @param stdClass $course The course
 * @param context_course $context Course context
 */
function local_webmonetization_extend_navigation_course($parentnode, $course, $context) {
    $parentnode->add(
            get_string('managepaymentpointer', 'local_webmonetization'),
            new \moodle_url('/local/webmonetization/editcontextpaymentpointer.php', ['contextid' => $context->id]),
            navigation_node::TYPE_CUSTOM,
            get_string('managepaymentpointer', 'local_webmonetization'),
            'managepaymentpointer',
            new pix_icon('i/payment', '')
    );
}

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
use local_webmonetization\receiptverifier;

defined('MOODLE_INTERNAL') || die();

function local_webmonetization_before_standard_html_head() {
    global $PAGE;

    if (empty(get_config('local_webmonetization', 'enable'))) {
        return '';
    }

    $path = $PAGE->url->get_path();

    if ($path == '/local/webmonetization/failed.php') {
        return '';
    }

    $monetizationsetuppage = $path == '/local/webmonetization/interstitial.php';

    $paymentpointer = contextpaymentpointer::nearest_paymentpointer($PAGE->context);

    if (!$paymentpointer) {
        return '';
    }

    $pointeraddress = $paymentpointer->get('paymentpointer');

    $verifier = receiptverifier::get_receiptverifier();
    if (isset($verifier)) {
        $pointeraddress = $verifier->gethandler($pointeraddress);
        $forcepayment =
                !empty($paymentpointer->get('forcepayment'))
                &&
                !has_capability('local/webmonetization:ignoreforcepayment', $PAGE->context);

        $lastverificationresult = receiptverifier::get_lastsessionverificationresult();
        if (!$monetizationsetuppage && $forcepayment && !isset($lastverificationresult)) {
            redirect(new moodle_url('/local/webmonetization/interstitial.php', ['contextid' => $PAGE->context->id]));
        } else if (!$monetizationsetuppage && $forcepayment && $lastverificationresult == false) {
            redirect(new moodle_url('/local/webmonetization/failed.php', ['contextid' => $PAGE->context->id]));
        }

        $jsparams = ['requiremonetization' => $forcepayment];

        if ($monetizationsetuppage) {
            $jsparams['wantsurl'] = $PAGE->context->get_url()->out();
        }

        $PAGE->requires->js_call_amd('local_webmonetization/handlereceipts', 'init', $jsparams);
    }

    $output = "<meta name='monetization' content='$pointeraddress'>";
    return $output;
}

function local_webmonetization_update_systempaymentpointer() {
    $systempointer = get_config('local_webmonetization', 'systempaymentpointer');
    $systemforcepayment = !empty(get_config('local_webmonetization', 'systemforcepayment'));
    $systemcontext = context_system::instance();
    $currentpointer = contextpaymentpointer::get_record(['contextid' => $systemcontext->id]);

    if (empty($systempointer)) {
        if ($currentpointer) {
            $currentpointer->delete();
        }
    } else {
        if (!$currentpointer) {
            $currentpointer = new contextpaymentpointer();
        }
        $currentpointer->set('contextid', $systemcontext->id);
        $currentpointer->set('paymentpointer', $systempointer);
        $currentpointer->set('forcepayment', $systemforcepayment);
        if (empty($currentpointer->get('id'))) {
            $currentpointer->create();
        } else {
            $currentpointer->update();
        }
    }
}

/**
 * Display the editcontextpaymentpointer link in the administration menus.
 *
 * @param settings_navigation $nav The settings navigation object
 * @param context $context Context
 */
function local_webmonetization_extend_settings_navigation(settings_navigation $nav, context $context) {
    if (!has_capability('local/webmonetization:managepaymentpointers', $context)) {
        return;
    }

    switch ($context->contextlevel) {
        case CONTEXT_MODULE:
            $navigation_node = $nav->get('modulesettings');
            break;
        case CONTEXT_COURSE:
            $navigation_node = $nav->get('courseadmin');
            break;
        case CONTEXT_COURSECAT:
            $navigation_node = $nav->get('categorysettings');
            break;
    }

    if (empty($navigation_node)) {
        return;
    }

    $navigation_node->add(
            get_string('managepaymentpointer', 'local_webmonetization'),
            new \moodle_url('/local/webmonetization/editcontextpaymentpointer.php', ['contextid' => $context->id]),
            navigation_node::TYPE_CUSTOM,
            get_string('managepaymentpointer', 'local_webmonetization'),
            'managepaymentpointer',
            new pix_icon('i/payment', '')
    );
}

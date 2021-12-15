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

defined('MOODLE_INTERNAL') || die;

$string['checkingbrowser'] = 'Checking browser for web monetization';
$string['cachedef_paymentstreams'] = 'Payment streams';
$string['enable'] = 'Enable';
$string['enable_desc'] = 'Embed payment pointers on pages where one exists in the current context or one of it\'s parent contexts';
$string['external'] = 'External';
$string['externalreceiptverifier'] = 'External receipt verifier';
$string['externalreceiptverifier_desc'] = 'External receipt verifier e.g. $webmonetization.org/api/receipts/';
$string['externalreceiptverifierverifyendpoint'] = 'Verify endpoint';
$string['externalreceiptverifierverifyendpoint_desc'] = 'Verify end point e.g. https://webmonetization.org/api/receipts/verify';
$string['forcepayment'] = 'Force payment';
$string['forcepayment_desc'] = 'Require the user to be using a web monetized browser.';
$string['internal'] = 'Internal';
$string['managepaymentpointer'] = 'Manage payment pointer';
$string['managepaymentpointerincontext'] = 'Manage payment pointer: {$a->contextname}';
$string['monetizationsettings'] = 'Web monetization';
$string['nonmonetizedbrowser'] = 'You need to access this content using a browser that supports Web Monetization, please visit <a href="https://coil.com">Coil</a> or <a href="https://webmonetization.org/#providers">Web Monetization</a> to find out more.';
$string['paymentpointer'] = 'Payment pointer';
$string['pluginname'] = 'Web monetization';
$string['privacy:metadata'] = 'The webmonetization plugin does not store or transmit any personal data.';
$string['receiptverifiertype'] = 'Receipt verifier';
$string['receiptverifiertype_desc'] = 'Choose which receipt verifier you wish to use, none will disable receipt verification, internal will use the plugin\'s internal verifier, external will let you specify a stand alone receipt verifier. ';
$string['systempaymentpointer'] = 'Default payment pointer';
$string['systempaymentpointer_desc'] = 'This payment pointer will be set at the system context.';
$string['systemforcepayment'] = 'Force payment (Default)';
$string['systemforcepayment_desc'] = 'Require the user to be using a web monetized browser.';
$string['webmonetization:ignoreforcepayment'] = 'Ignore force payment setting';
$string['webmonetization:managepaymentpointers'] = 'Manage payment pointers';

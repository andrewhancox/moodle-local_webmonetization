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

namespace local_webmonetization;

defined('MOODLE_INTERNAL') || die();

use curl;
use external_api;
use external_description;
use external_function_parameters;
use external_value;

class external extends external_api {
    public static function handlereceipt_parameters(): external_function_parameters {
        $params = [
                'receipt'   => new external_value(
                        PARAM_RAW_TRIMMED,
                        'Receipt',
                        VALUE_REQUIRED
                ),
                'contextid' => new external_value(
                        PARAM_INT,
                        'Context id of monetizationprogress',
                        VALUE_REQUIRED
                )
        ];
        return new external_function_parameters($params);
    }

    public static function handlereceipt($receipt, $contextid): bool {
        self::validate_parameters(self::handlereceipt_parameters(), array(
                'receipt'   => $receipt,
                'contextid' => $contextid
        ));

        $verifier = receiptverifier::get_receiptverifier();
        $result = $verifier->verify($receipt);

        receiptverifier::set_lastsessionverificationresult($result);
        return $result;
    }

    public static function handlereceipt_returns(): external_description {
        return new external_value(PARAM_BOOL, 'True if successful.');
    }
}

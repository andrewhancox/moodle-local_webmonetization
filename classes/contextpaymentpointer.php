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

use context;
use core\persistent;

class contextpaymentpointer extends persistent {
    const TABLE = 'local_wm_ctxpaymentpointer';

    protected static function define_properties() {
        return [
                'contextid'      => [
                        'type'        => PARAM_INT,
                        'description' => 'Context to use payment pointer',
                ],
                'forcepayment'      => [
                        'type'        => PARAM_BOOL,
                        'description' => 'Context to use payment pointer',
                ],
                'paymentpointer' => [
                        'type'        => PARAM_TEXT,
                        'description' => 'Payment pointer',
                ],
        ];
    }

    /**
     * Get the most appropriate payment pointer to display in a given context.
     * @param context $context Course context
     */
    public static function nearest_paymentpointer(context $context) {
        global $DB;

        $contextids = $context->get_parent_context_ids(true);
        list($sql, $params) = $DB->get_in_or_equal($contextids, SQL_PARAMS_NAMED);
        $pointers = self::get_records_select("contextid $sql", $params);

        if (!$pointers) {
            return false;
        }

        return reset($pointers);
    }
}

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

function xmldb_local_webmonetization_upgrade($oldversion) {
    global $CFG, $DB;

    $result = true;
    $dbman = $DB->get_manager();

    if ($oldversion < 2021012506) {
        set_config('receiptseed', base64_encode(random_bytes(32)), 'local_webmonetization');
        upgrade_plugin_savepoint(true, 2021012506, 'local', 'webmonetization');
    }

    if ($oldversion < 2021012507) {
        $table = new xmldb_table('local_wm_ctxpaymentpointer');
        $field = new xmldb_field('forcepayment', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, 0, 0);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2021012507, 'local', 'webmonetization');
    }

    return $result;
}

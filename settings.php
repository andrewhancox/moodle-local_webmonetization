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

if ($hassiteconfig) {
    $monetizationsettings = new admin_settingpage('monetizationsettings',
            new lang_string('monetizationsettings', 'local_webmonetization'),
            'local/webmonetization:managepaymentpointers', false, context_system::instance()
    );

    $monetizationsettings->add(new admin_setting_configcheckbox('local_webmonetization/enable',
            new lang_string('enable', 'local_webmonetization'),
            new lang_string('enable_desc', 'local_webmonetization'), false));

    require_once "$CFG->dirroot/local/webmonetization/lib.php";
    $setting = new admin_setting_configtext('local_webmonetization/systempaymentpointer',
            new lang_string('systempaymentpointer', 'local_webmonetization'),
            new lang_string('systempaymentpointer_desc', 'local_webmonetization'),
            '',
            PARAM_TEXT
    );
    $monetizationsettings->add($setting);

    $setting = new admin_setting_configcheckbox('local_webmonetization/systemforcepayment',
            new lang_string('systemforcepayment', 'local_webmonetization'),
            new lang_string('systemforcepayment_desc', 'local_webmonetization'),
            false,
            PARAM_TEXT
    );
    $setting->set_updatedcallback('local_webmonetization_update_systempaymentpointer');
    $monetizationsettings->add($setting);

    $options = array(
            '' => new lang_string('none'),
            'internal' => new lang_string('internal', 'local_webmonetization'),
            'external' => new lang_string('external', 'local_webmonetization')
    );
    $setting = new admin_setting_configselect('local_webmonetization/receiptverifiertype',
            new lang_string('receiptverifiertype', 'local_webmonetization'),
            new lang_string('receiptverifiertype_desc', 'local_webmonetization'),
            '',
            $options);
    $monetizationsettings->add($setting);

    $setting = new admin_setting_configtext('local_webmonetization/externalreceiptverifier',
            new lang_string('externalreceiptverifier', 'local_webmonetization'),
            new lang_string('externalreceiptverifier_desc', 'local_webmonetization'),
            '',
            PARAM_TEXT
    );
    $monetizationsettings->add($setting);

    $setting = new admin_setting_configtext('local_webmonetization/externalreceiptverifierverifyendpoint',
            new lang_string('externalreceiptverifierverifyendpoint', 'local_webmonetization'),
            new lang_string('externalreceiptverifierverifyendpoint_desc', 'local_webmonetization'),
            '',
            PARAM_TEXT
    );
    $monetizationsettings->add($setting);

    $ADMIN->add('localplugins', $monetizationsettings);
}

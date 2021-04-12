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

define(['core/ajax'], function (ajax) {
    return {
        /**
         * Factory method returning instance of the handlereceipts
         *
         * @return {handlereceipts}
         */
        // switch to using timer, 60 seconds but reset each time a monetizationprogress fires successfully.
        init: function (requiremonetization, wantsurl) {
            if (document.monetization) {
                document.monetization.addEventListener('monetizationprogress', event => {
                    var req = ajax.call([
                        {
                            methodname: 'local_webmonetization_handlereceipt', args: {
                                receipt: event.detail.receipt,
                                contextid: M.cfg.contextid
                            }
                        }
                    ]);

                    if (requiremonetization) {
                        var redirecturl = M.cfg.wwwroot + '/local/webmonetization/interstitial.php?contextid=' + M.cfg.contextid;
                        req[0].done(function (result) {
                            if (result !== true) {
                                window.location.href = redirecturl;
                            } else if (wantsurl) {
                                window.location.href = wantsurl;
                            }
                        }).fail(function () {
                            window.location.href = redirecturl;
                        });
                    }
                });
            } else if (requiremonetization) {
                var redirecturl = M.cfg.wwwroot + '/local/webmonetization/failed.php?contextid=' + M.cfg.contextid;
                window.location.href = redirecturl;
            }
        }
    };
});

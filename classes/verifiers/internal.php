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

namespace local_webmonetization\verifiers;

use local_webmonetization\receiptverifier;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

class internal extends receiptverifier {
    protected function verifyhmac($binaryreceipt): bool {
        global $CFG;

        require_once("$CFG->dirroot/local/webmonetization/lib/interledgerphp/autoload.php");
        $receipthandler = new \interledgerphp\receipthandler(
                get_config('local_webmonetization', 'receiptseed')
        );

        return $receipthandler->verify_hmac($binaryreceipt);
    }

    public function gethandler($paymentpointer): string {
        $url = new moodle_url("/local/webmonetization/handlers/receiptverifierproxy.php", ['paymentpointer' => $paymentpointer]);
        return $url->out(false);
    }
}

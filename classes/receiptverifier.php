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

use cache;

defined('MOODLE_INTERNAL') || die();

abstract class receiptverifier {
    private const LASTSESSIONVERIFICATIONRESULTPROPERTYNAME = 'receiptverifier_lastsessionverificationresult';

    protected abstract function verifyhmac($binaryreceipt): bool;

    public function verify($base64binreceipt): bool {
        global $CFG;

        require_once("$CFG->dirroot/local/webmonetization/lib/interledgerphp/autoload.php");
        $receipthandler = new \interledgerphp\receipthandler();

        $binreceipt = base64_decode($base64binreceipt);

        try {
            $receipt = $receipthandler->parse_receipt($binreceipt);
        } catch (\Exception $exception) {
            return false;
        }

        if (!$this->verifyhmac($binreceipt)) {
            return false;
        }

        $decodednonce = base64_encode($receipt->nonce);

        $cache = cache::make('local_webmonetization', 'paymentstreams');
        $receivedamounts = $cache->get($decodednonce);

        if ($receipt->totalreceived == 0) {
            return false;
        } else if ($receivedamounts === false) {
            $receivedamounts = [$receipt->totalreceived];
        } else if (in_array($receipt->totalreceived, $receivedamounts)) {
            return false;
        } else {
            $receivedamounts[] = $receipt->totalreceived;
        }

        $cache->set($decodednonce, $receivedamounts);

        return true;
    }

    public abstract function gethandler($paymentpointer): string;

    public static function get_lastsessionverificationresult(): ?bool {
        global $SESSION;

        if (!isset($SESSION->{self::LASTSESSIONVERIFICATIONRESULTPROPERTYNAME})) {
            return null;
        } else {
            return !empty($SESSION->{self::LASTSESSIONVERIFICATIONRESULTPROPERTYNAME});
        }
    }

    public static function set_lastsessionverificationresult($result) {
        global $SESSION;
        $SESSION->{self::LASTSESSIONVERIFICATIONRESULTPROPERTYNAME} = $result;
    }

    public static function get_receiptverifier(): ?receiptverifier {
        switch (get_config('local_webmonetization', 'receiptverifiertype')) {
            case "none":
                return null;
            case "internal":
                return new \local_webmonetization\verifiers\internal();
            case "external":
                return new \local_webmonetization\verifiers\external();
        }
    }
}

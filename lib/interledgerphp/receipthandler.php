<?php

/**
 * @package interledgerphp
 * @author Andrew Hancox <andrewdchancox@googlemail.com>
 * @author Open Source Learning <enquiries@opensourcelearning.co.uk>
 * @link https://opensourcelearning.co.uk
 * @link https://webmonetization.org
 * @link https://github.com/andrewhancox/interledgerphp
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2021, Andrew Hancox
 */

namespace interledgerphp;

/**
 * Class receipthandler
 *
 * @package interledgerphp
 */
class receipthandler {
    /**
     * @var string
     */
    private $receiptseed;

    /**
     * @var string
     */
    private const RECEIPT_SECRET_GENERATION_STRING = 'receipt_secret';

    public function __construct(?string $receiptseed = null) {
        $this->receiptseed = $receiptseed;
    }

    public function generate_receipt_nonce(): string {
        return random_bytes(16);
    }

    public function generate_receipt_secret(string $nonce): string {
        $keygen = hash_hmac('sha256', utf8_encode(self::RECEIPT_SECRET_GENERATION_STRING), $this->receiptseed, true);
        return hash_hmac('sha256', $nonce, base64_encode($keygen), true);
    }

    /**
     * Parses out the information from a stream receipt conforming to:
     * https://interledger.org/rfcs/0039-stream-receipts/
     *
     * @param string $binaryreceipt
     * @return receipt
     * @throws receiptexception
     */
    public function parse_receipt(string $binaryreceipt): receipt {
        $arrayofbytes = str_split($binaryreceipt);

        if (count($arrayofbytes) !== 58) {
            throw new receiptexception('incorrect size');
        }

        $arrayofbinarybytes = [];
        foreach ($arrayofbytes as $byte) {
            $arrayofbinarybytes[] = sprintf("%08b", ord($byte));
        }

        $version = bindec(array_slice($arrayofbinarybytes, 0, 1)[0]);

        if ($version !== 1) {
            throw new receiptexception('unsupported receipt version');
        }

        $receipt = new receipt();
        $receipt->nonce = implode('', array_slice($arrayofbytes, 1, 16));
        $receipt->streamid = bindec(array_slice($arrayofbinarybytes, 17, 1)[0]);
        $receipt->totalreceived = bindec(implode('', array_slice($arrayofbinarybytes, 18, 8)));

        return $receipt;
    }

    /**
     * Parses out the information from a stream receipt conforming to:
     * https://interledger.org/rfcs/0039-stream-receipts/
     *
     * @param string $binaryreceipt
     * @return receipt
     * @throws receiptexception
     */
    public function verify_hmac(string $binaryreceipt): bool {
        $arrayofbytes = str_split($binaryreceipt);

        if (count($arrayofbytes) !== 58) {
            throw new receiptexception('incorrect size');
        }

        $arrayofbinarybytes = [];
        foreach ($arrayofbytes as $byte) {
            $arrayofbinarybytes[] = sprintf("%08b", ord($byte));
        }

        $nonce = implode('', array_slice($arrayofbytes, 1, 16));
        $receiptbody = implode('', array_slice($arrayofbytes, 0, 26));
        $receipthmac = implode('', array_slice($arrayofbytes, 26, 32));

        $receiptsecret = $this->generate_receipt_secret($nonce);

        $calculatedhmac = hash_hmac('sha256', $receiptbody, $receiptsecret, true);

        if ($receipthmac != $calculatedhmac) {
            return false;
        }

        return true;
    }
}

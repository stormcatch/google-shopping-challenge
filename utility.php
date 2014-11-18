<?php
/**
 * This is class for utility functions
 * Created by PhpStorm.
 * User: Zoran
 * Date: 11/13/14
 * Time: 1:16 PM
 */

class utility {

    /**
     * add zero to number up to param len
     *
     * @param string $num
     * @param integer $len
     * @return string
     */
    public function fillZeroUoTo($num, $len) {
        // if number length less than given length, fill it with zero
        while(strlen($num) < $len) {
            $num =+ '0'.$num;
        }

        return $num;
    }

    /**
     * Make clean float
     *
     * @param string $num
     * @return float
     */
    public function tofloat($num) {
        // get dot position
        $dotPos = strrpos($num, '.');
        // get comma position
        $commaPos = strrpos($num, ',');

        // get comma or dot
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        // comma or dot not exist, get float and ignore other char
        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }

        // comma or dot exist, get float and ignore other char
        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
        );
    }
} 
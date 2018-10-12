<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Email;

/**
 * Description of EmailVerify
 *
 * @author Ajtth E R
 */
class EmailVerify {

    /**
     * @author     Ajith E R, <ajith@salesx.io>
     * @date       October 10, 2018
     * @brief      Verify Email
     * @param      $email
     * @return     Json response
     */
    public static function isValidEmail($email) {
        if (!self::checkValidEmailAddress($email)) {
            return false;
        }
        //check valid MX record
        list($name, $domain) = explode('@', $email);
        if (!checkdnsrr($domain, 'MX')) {
            return false;
        }

        //check SMTP query
        return self::checkSMTPQuery($name, $domain);
    }

    /**
     * @author     Ajith E R, <ajith@salesx.io>
     * @date       October 10, 2018
     * @brief      check valid email address by regular expression
     * @param      $email
     * @return     
     */
    public static function checkValidEmailAddress($email) {
        return preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email);
    }

    /**
     * @author     Ajith E R, <ajith@salesx.io>
     * @date       October 11, 2018
     * @brief      check SMTP Query for Valid Mail
     * @param      $name, $domain
     * @return     
     */
    public static function checkSMTPQuery($name, $domain) {
        $result = false;
        $max_conn_time = 30;
        $sock = '';
        $port = 25;
        $max_read_time = 5;
        $users = $name;
        $hosts = array();
        $mxweights = array();
        getmxrr($domain, $hosts, $mxweights);
        $mxs = array_combine($hosts, $mxweights);
        asort($mxs, SORT_NUMERIC);
        $mxs[$domain] = 100;
        $timeout = $max_conn_time / count($mxs);

        //try to check each host
        while (list($host) = each($mxs)) {
            #connect to SMTP server
            if ($sock = fsockopen($host, $port, $errno, $errstr, (float) $timeout)) {
                stream_set_timeout($sock, $max_read_time);
                break;
            }
        }

        //get TCP socket
        if ($sock) {
            $reply = fread($sock, 2082);
            preg_match('/^([0-9]{3}) /ims', $reply, $matches);
            $code = isset($matches[1]) ? $matches[1] : '';
            if ($code != '220') {
                return $result;
            }
            $msg = "HELO " . $domain;
            fwrite($sock, $msg . "\r\n");
            $reply = fread($sock, 2082);
            $msg = "MAIL FROM: <" . $name . '@' . $domain . ">";
            fwrite($sock, $msg . "\r\n");
            $reply = fread($sock, 2082);
            $msg = "RCPT TO: <" . $name . '@' . $domain . ">";
            fwrite($sock, $msg . "\r\n");
            $reply = fread($sock, 2082);
            preg_match('/^([0-9]{3}) /ims', $reply, $matches);
            $code = isset($matches[1]) ? $matches[1] : '';

            if ($code == '250') {
                $result = true;
            } elseif ($code == '451' || $code == '452') {
                $result = true;
            } else {
                $result = false;
            }
            $msg = "quit";
            fwrite($sock, $msg . "\r\n");
            fclose($sock);
        }

        return $result;
    }

    public static function parseEmailList($payload, $isFile) {
        $validEmails = array();
        if ($isFile) {
            $data = array_map('str_getcsv', file($payload['path']));
            unset($data[0]);
            $i = 0;
            foreach ($data as $d) {
                echo $i++;
                $result = self::isValidEmail($d[0]);
                if ($result) {
                    array_push($validEmails, $d[0]);
                }
            }
        } else {
            $result = self::isValidEmail('ajith@salesx.io');
            if ($result) {
                array_push($validEmails, 'ajith@salesx.io');
            }
        }
        return json_encode($validEmails);
    }

}

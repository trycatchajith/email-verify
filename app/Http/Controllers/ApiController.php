<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Email\EmailVerify;
use Maatwebsite\Excel\Excel;

/**
 * Description of ApiController
 *
 * @author Ajith E R
 */
class ApiController extends Controller {

    public function importEmailList(Request $request) {
        $payload['path'] = $request->file('email-list')->getRealPath();
        return EmailVerify::parseEmailList($payload, true);
    }

    public function verifyEmailIds(Request $request) {
        $payload = $request->all();
        return EmailVerify::parseEmailList($payload, false);
    }

}

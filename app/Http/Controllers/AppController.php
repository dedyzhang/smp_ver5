<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class AppController extends Controller
{
    public function redirect(Request $request): View
    {
        if ($request->cookie('link') !== null) {
            return
                redirect()->to($request->cookie('link'))->send();
        } else {
            return view('redirect');
        }
    }
    public function redirectUpdate(Request $request)
    {
        $link = $request->link;
        Cookie::queue('link', $link, 10080);
    }
}

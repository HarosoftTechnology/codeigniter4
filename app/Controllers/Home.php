<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data['pageTitle'] = "Home Page";
        return view('login', $data);
    }
}

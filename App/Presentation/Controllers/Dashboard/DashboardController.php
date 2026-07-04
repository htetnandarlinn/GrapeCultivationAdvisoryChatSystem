<?php

namespace App\Presentation\Controllers\Dashboard;

use App\Presentation\Views\View;

class DashboardController
{
    public function home(): void
    {
        View::render('home');
    }

    public function about(): void
    {
        View::render('about');
    }

    public function contact(): void
    {
        View::render('contact');
    }
}

    

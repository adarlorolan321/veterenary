<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentCalendarController extends Controller
{
    public function index(){
        return Inertia::render('AppointmentCalendar/Index');
    }

    public function show(Request $request){
       return  $request;
    } 
    public function store(Request $request){
        dd($request);
    }
}

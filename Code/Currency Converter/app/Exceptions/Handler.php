<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

use Throwable;

class Handler extends ExceptionHandler{


    public function render($request, Throwable $exception){

        if ($exception instanceof TokenMismatchException) {
            session()->flash('error', 'Your session has expired. Please log in again.');
            return redirect()->route('showLogin');
        }
        return parent::render($request, $exception);
    }
}

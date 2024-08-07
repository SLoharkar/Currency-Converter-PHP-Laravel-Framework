<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CurrenciesExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IPController;



// HomeController Routes
Route::controller(HomeController::class)->group(function (){

    // Home page
    Route::get("/","homepage")
        ->name("home");

    // Show login form
    Route::get('/login','showLoginForm')
        ->name('showLogin');
    
    // Handle login form submission
    Route::post('/login','login')
        ->name('login');
    
    // Show register form
    Route::get("/register","showRegisterForm")
        ->name("showRegister");

    // Handle register form submission
    Route::post("/register","register")
        ->name("register");

    // Show forgot password form
    Route::get("/forgot-password","showForgotPasswordForm")
        ->name("showForgotPassword");

    // Handle forgot password form submission
    Route::post("/forgot-password","forgotPassword")
        ->name("forgot_password");

    // User dashboard
    Route::get("/dashboard","dashboard")
        ->name("dashboard");

    // Logout
    Route::get("/logout","logout")
        ->name("logout");
    
});


// Routes that require authentication
Route::middleware(['Auth'])->group(function () {


    // CurrencyController Routes
    Route::controller(CurrencyController::class)->group(function (){
        
        // Show currency converter form for admin or user
        Route::get("/{role}/currency-converter","currencyConverterForm")
            ->where('role', 'admin|user')
            ->name("currency.converter_form");

        // Handle currency conversion for admin or user
        Route::post("/{role}/currency-converter","currencyConverter")
            ->where('role', 'admin|user')
            ->name("currency.converter");

    });


    // CurrenciesExportController Routes
    Route::controller(CurrenciesExportController::class)->group(function (){
        
        // Show currencies export form
        Route::get("/admin/currencies-export","showCurrenciesExportForm")
            ->name("currencies.export");
        
        // Export currencies to Excel
        Route::post("/admin/currencies-export-excel","exportToExcel")
            ->name("currencies.export_excel");

        // Export currencies to Database
        Route::post("/admin/currencies-export-database","exportToDatabase")
            ->name("currencies.export_database");

    });


    // AdminController Routes
    Route::controller(AdminController::class)->group(function (){
        
        // User management
        Route::get("/admin/user-management","userManagement")
            ->name("admin.user_management_form");

        // Delete user by ID
        Route::delete("/admin/user-delete}","userDeleteById")
            ->name("admin.user_delete");

        // Show user update form
        Route::match(['get','post'],"/admin/user-update","showUserUpdate")
            ->name("admin.user_update_form");        

        // Update user by ID
        Route::put("/admin/user-update","userUpdateById")
            ->name("admin.user_update");
        
    });


    // IPController Routes
    Route::controller(IPController::class)->group(function (){

        // IP management
        Route::get("/admin/ip-management","ipManagement")
            ->name("ip.management");

        // Add IP address
        Route::post("/admin/ip-management","ipAddressAdd")
            ->name("ip.management_add");

        // Update IP address
        Route::put("/admin/ip-management","ipAddressUpdate")
            ->name("ip.management_update");

        // Delete IP address
        Route::delete("/admin/ip-management","ipAddressDelete")
            ->name("ip.management_del");       

    });

});

?>
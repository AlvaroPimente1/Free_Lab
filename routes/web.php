<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::resourceVerbs([
    'create' => 'cadastro',
    'edit' => 'editar'
]);

Route::group([
    'middleware' => ['auth', 'auth.unique.user']
], function() {

    Route::get('/', 'HomeController@index')->name('home')->middleware('expired.home');

    Route::group([
        'middleware'    => ['expired']
    ], function() {
        
        Route::group([
            'prefix'        => 'patients',
            'as'            => 'patients.'
        ], function() {
            Route::get('/index', 'PatientController@index')->name('index');
            Route::get('/create/{lab_id}', 'PatientController@create')->name('create');
            Route::post('/store/{lab_id}', 'PatientController@store')->name('store');
            Route::get('/connect/{id}', 'PatientController@connect')->name('connect');
            Route::get('/show/{id}/{lab_id}', 'PatientController@show')->name('show');
            Route::get('/edit/{id}/{lab_id}', 'PatientController@edit')->name('edit');
            Route::put('/update/{id}/{lab_id}', 'PatientController@update')->name('update');
        });

        Route::get('/reports/create', 'ReportController@showProcedures')->name('showProcedures');
        Route::get('/reports/create/{procedure}/{lab_id}', 'ReportController@create')->name('createReport');
        Route::post('/reports', 'ReportController@store')->name('storeReport');
        Route::get('/reports', 'ReportController@index')->name('reportIndex');
        Route::get('/reports/{report}', 'ReportController@show')->name('reportShow');

        Route::resource('procedures', 'ProcedureController');
        Route::resource('users', 'SupportController');

        Route::get('/support', 'SupportController@home')->name('support.home');
        Route::get('/support/connect/{id}', 'SupportController@connect')->name('support.connect');
        Route::post('/support/connecting/{id}', 'SupportController@connecting')->name('support.connecting');
        Route::get('/support/statistics', 'SupportController@statistics')->name('support.statistics');
        Route::get('/support/system', 'SupportController@system')->name('support.system');
        Route::get('/support/show/{id}', 'SupportController@show')->name('support.show');

        Route::resource('laboratories', 'LaboratoryController');
        Route::get('/laboratories/connect/{id}', 'LaboratoryController@connect')->name('laboratories.connect');
        Route::get('/laboratories/disconnect/{id}', 'LaboratoryController@disconnect')->name('laboratories.disconnect');

        Route::resource('administrators', 'AdministratorController');
        Route::get('/administrators/connect/{id}', 'AdministratorController@connect')->name('administrators.connect');
        Route::get('/manualdownload', 'AdministratorController@manualdownload')->name('manualdownload');

        Route::resource('employees', 'EmployeesController');
    }); 
});


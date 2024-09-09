<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// Define os verbos personalizados para create e edit
Route::resourceVerbs([
    'create' => 'cadastro',
    'edit' => 'editar'
]);

// Agrupamento de rotas que exigem autenticação e verificação de usuário único
Route::group([
    'middleware' => ['auth', 'auth.unique.user']
], function() {

    // Rota inicial
    Route::get('/', 'HomeController@index')->name('home')->middleware('expired.home');

    // Agrupamento de rotas que exigem verificação adicional de sessão expirada
    Route::group([
        'middleware' => ['expired']
    ], function() {
        
        // Rotas relacionadas a pacientes
        Route::group([
            'prefix' => 'patients',
            'as' => 'patients.'
        ], function() {
            Route::get('/index', 'PatientController@index')->name('index');
            Route::get('/create/{lab_id}', 'PatientController@create')->name('create');
            Route::post('/store/{lab_id}', 'PatientController@store')->name('store');
            Route::get('/connect/{id}', 'PatientController@connect')->name('connect');
            Route::get('/show/{id}/{lab_id}', 'PatientController@show')->name('show');
            Route::get('/edit/{id}/{lab_id}', 'PatientController@edit')->name('edit');
            Route::put('/update/{id}/{lab_id}', 'PatientController@update')->name('update');
        });

        // Relatórios
        Route::get('/reports/create', 'ReportController@showProcedures')->name('showProcedures');
        Route::get('/reports/create/{procedure}/{lab_id}', 'ReportController@create')->name('createReport');
        Route::post('/reports', 'ReportController@store')->name('storeReport');
        Route::get('/reports', 'ReportController@index')->name('reportIndex');
        Route::get('/reports/{report}', 'ReportController@show')->name('reportShow');

        // **Rotas de Procedures**
        // Define as rotas RESTful para procedures
        Route::resource('procedures', 'ProcedureController');

        // Rota personalizada para inativar um procedimento
        Route::put('/procedures/{procedure}/inactivate', 'ProcedureController@inactivate')->name('procedures.inactivate');

        // Rotas de usuários (support)
        Route::resource('users', 'SupportController');

        // Rotas adicionais de suporte
        Route::get('/support', 'SupportController@home')->name('support.home');
        Route::get('/support/connect/{id}', 'SupportController@connect')->name('support.connect');
        Route::post('/support/connecting/{id}', 'SupportController@connecting')->name('support.connecting');
        Route::get('/support/statistics', 'SupportController@statistics')->name('support.statistics');
        Route::get('/support/system', 'SupportController@system')->name('support.system');
        Route::get('/support/show/{id}', 'SupportController@show')->name('support.show');

        // Rotas relacionadas a laboratórios
        Route::resource('laboratories', 'LaboratoryController');
        Route::get('/laboratories/connect/{id}', 'LaboratoryController@connect')->name('laboratories.connect');
        Route::get('/laboratories/disconnect/{id}', 'LaboratoryController@disconnect')->name('laboratories.disconnect');

        // Rotas relacionadas a administradores
        Route::resource('administrators', 'AdministratorController');
        Route::get('/administrators/connect/{id}', 'AdministratorController@connect')->name('administrators.connect');
        Route::get('/manualdownload', 'AdministratorController@manualdownload')->name('manualdownload');

        // Rotas relacionadas a funcionários
        Route::resource('employees', 'EmployeesController');
    }); 
});

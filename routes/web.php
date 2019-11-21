<?php


//pages
Route::get('{path}', function() {
  return view('index');
})->where('path', '.*');
// Route::get('/about', 'PagesController@about');

// Route::get('/login', 'HomeController@index')->name('login');

// //transactions
// Route::resource('transactions', 'TransactionsController');

// //clients
// Route::resource('clients', 'ClientsController');

// //companies
// Route::resource('companies', 'CompaniesController');

// //company types
// Route::resource('company-types', 'CompanyTypesController');

// //services
// Route::resource('services', 'ServicesController');

// //transaction subjects
// Route::resource('transaction-subjects', 'TransactionSubjectsController');

//chart
// Route::view('/chart', 'chart');

// Route::view('/manage', 'manage');

// Route::view('/twilio', 'test');


// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Route::view('/worker', 'worker');

// TWILIO
// Route::post('/call/respond', 'Twilio\IncomingCallController@respondToUser');
// Route::post('/call/enqueue', 'Twilio\EnqueueCallController@enqueueCall');
// Route::post('/call/assign', 'Twilio\CallbackController@assignTask');

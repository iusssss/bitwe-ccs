<?php

use Illuminate\Http\Request;
use App\Events\TicketCreated;
use App\Events\TicketUpdateCreated;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
	Auth::routes();
	Route::get('/user', function (Request $request) {
	    return $request->user();
	});
	Route::post('/checkCurrentPassword', 'UsersController@checkCurrentPassword');
	Route::post('logout', 'AuthController@logout');
	// Ticket ROUTES
	// All Tickets
	Route::get('/tickets', 'TicketController@index');
	// Last Ticket
	Route::get('/lastTicket', 'TicketController@lastTicket');
	// Create Ticket
	Route::post('/ticket', 'TicketController@store');
	// Delete Ticket
	Route::delete('/ticket/{id}', 'TicketController@destroy');

	Route::post('/email', 'EmailController@sendMail');

	// Scorecard
	Route::post('/scorecard', 'ScorecardController@store');
	// Route::get('/tickettest', 'TicketController@test');
});

// reset password
// Route::get('password/reset/{token}', 'Auth\PasswordController@showResetForm')
Route::post('password/email', 'PasswordController@sendResetLinkEmail');
Route::get('password/token/{token}', 'PasswordController@validateToken');
Route::post('password/reset', 'PasswordController@reset');

// Temp Client
Route::get('/unregistered', 'TempClientController@unregistered');
Route::post('/tempClient', 'TempClientController@store');
Route::get('/tempClient/{ticketId}', 'TempClientController@show');
Route::put('/tempClient/{ticketId}', 'TempClientController@update');
Route::delete('/tempClient/{id}', 'TempClientController@destroy');
// Ticket Updates
Route::get('/ticketUpdate/{ticketId}', 'TicketUpdatesController@index');
// Ticket Create Update
Route::post('/ticketUpdate', 'TicketUpdatesController@store');
// Tickets This Day
Route::get('/ticketsThisDay', 'TicketController@ticketsThisDay');
// Tickets Per Week
Route::get('/ticketsPerWeek', 'TicketController@ticketsPerWeek');
// Tickets Per Day in Month
Route::get('/ticketsPerDayInMonth', 'TicketController@ticketsPerDayInMonth');
// Tickets Per Month
Route::get('/ticketsPerMonth', 'TicketController@ticketsPerMonth');
// Tickets This Year
Route::get('/ticketsThisYear', 'TicketController@ticketsThisYear');
// Single Ticket
Route::get('/ticket/{id}', 'TicketController@show');
// Update Ticket
Route::put('/ticket/{id}', 'TicketController@update');
// Export Tickets
Route::get('tickets/export/{filter}', 'TicketController@export');

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

//test route
// Route::post('testRt', 'TicketController@test');

//real-time event
Route::get('/fire', function() {
	$ticket = 'test';
	// event(new TicketUpdateCreated($ticket));
	event(new TicketCreated($ticket));
	return 'ticketupdate';
});

// CRITERIA ROUTES
// All Criterias
Route::get('criterias', 'CriteriasController@index');
// Create Criteria
Route::post('criteria', 'CriteriasController@store');
// Update Criteria
Route::put('criteria/{id}', 'CriteriasController@update');
// Delete Criteria
Route::delete('criteria/{id}', 'CriteriasController@destroy');
Route::post('question', 'CriteriasController@storeQuestion');
Route::put('question/{id}', 'CriteriasController@updateQuestion');
Route::get('questions/{id}', 'CriteriasController@showQuestions');
Route::delete('question/{id}', 'CriteriasController@destroyQuestion');

// USER ROUTES
// All Users
Route::get('users', 'UsersController@index');
Route::get('users-all', 'UsersController@all');
// Export Users
Route::get('users/export', 'UsersController@export');
// Single User
Route::get('user/{id}', 'UsersController@show');
// Create User
Route::post('user', 'UsersController@store');
// Update User
Route::put('user/{id}', 'UsersController@update');
// Delete User
Route::delete('user/{id}', 'UsersController@destroy');
// Change Pass
Route::put('user/changepass/{id}', 'UsersController@changePassword');

// CLIENT ROUTES
// All Clients
Route::get('clients', 'ClientsController@index');
// Export Users
Route::get('clients/export/{company_id}', 'ClientsController@export');
// 
Route::post('client/fileUpload', 'ClientsController@fileUpload');
// 
Route::post('client/fileSave', 'ClientsController@fileSave');
// Clients by Company
Route::get('clients/company/{id}', 'ClientsController@byCompany');
// Clients by Phone
Route::get('client/phone/{phone}', 'ClientsController@byPhone');
// Single Client
Route::get('client/{id}', 'ClientsController@show');
// Create Client
Route::post('client', 'ClientsController@store');
// Update Client
Route::put('client/{id}', 'ClientsController@update');
// Delete Client
Route::delete('client/{id}', 'ClientsController@destroy');

// COMPANY ROUTES
// All Companies Paginated
Route::get('companies', 'CompaniesController@index');
// All Companies
Route::get('allcompanies', 'CompaniesController@all');
// Export Companies
Route::get('companies/export', 'CompaniesController@export');
// 
Route::post('company/fileUpload', 'CompaniesController@fileUpload');
// 
Route::post('company/fileSave', 'CompaniesController@fileSave');
// Single Company
Route::get('company/{id}', 'CompaniesController@show');
// Create Company
Route::post('company', 'CompaniesController@store');
// Update Company
Route::put('company/{id}', 'CompaniesController@update');
// Delete Company
Route::delete('company/{id}', 'CompaniesController@destroy');

// COMPANY TYPE ROUTES
// All Company Types
Route::get('companytypes', 'CompanyTypesController@index');
// Single Company Types 
Route::get('companytype/{id}', 'CompanyTypesController@show');
// Create Company Types
Route::post('companytype', 'CompanyTypesController@store');
// Update Company Types
Route::put('companytype/{id}', 'CompanyTypesController@update');
// Delete Company Types
Route::delete('companytype/{id}', 'CompanyTypesController@destroy');

// SERVICE ROUTES
// All Services
Route::get('services', 'ServicesController@index');
// Export Services
Route::get('services/export', 'ServicesController@export');
// Single Service
Route::get('service/{id}', 'ServicesController@show');
// Create Service
Route::post('service', 'ServicesController@store');
// Update Service
Route::put('service/{id}', 'ServicesController@update');
// Delete Service
Route::delete('service/{id}', 'ServicesController@destroy');

// // Ticket ROUTES
// // All Tickets
// Route::get('tickets', 'TicketController@index');
// // Last Ticket
// Route::get('lastTicket', 'TicketController@lastTicket');
// // Single Ticket
// Route::get('ticket/{id}', 'TicketController@show');
// // Create Ticket
// Route::post('ticket', 'TicketController@store');
// // Update Ticket
// Route::put('ticket/{id}', 'TicketController@update');
// // Delete Ticket
// Route::delete('ticket/{id}', 'TicketController@destroy');

// TicketStatus ROUTES
// All Status
Route::get('ticketStatuses', 'TicketStatusController@index');

// TRANSACTION ROUTES
// All Transactions
Route::get('transactions', 'TransactionsController@index');
// Single Transaction
Route::get('transaction/{id}', 'TransactionsController@show');
// Create Transaction
Route::post('transaction', 'TransactionsController@store');
// Update Transaction
Route::put('transaction/{id}', 'TransactionsController@update');
// Delete Transaction
Route::delete('transaction/{id}', 'TransactionsController@destroy');

// TRANSACTION SUBJECT ROUTES
// All Transaction Subjects
Route::get('transactionSubjects', 'TransactionSubjectsController@index');
// Single Transaction Subjects
Route::get('transactionSubject/{id}', 'TransactionSubjectsController@show');
// Create Transaction Subjects
Route::post('transactionSubject', 'TransactionSubjectsController@store');
// Update Transaction Subjects
Route::put('transactionSubject/{id}', 'TransactionSubjectsController@update');
// Delete Transaction Subjects
Route::delete('transactionSubject/{id}', 'TransactionSubjectsController@destroy');

// Search
// Route::get('search', 'SearchController@search');
Route::get('search', ['as' => 'search', 'uses' => 'SearchController@search']);

// TWILIO
Route::post('/call/respond', 'Twilio\IncomingCallController@respondToUser');
Route::post('/call/enqueue', 'Twilio\EnqueueCallController@enqueueCall');
Route::post('/call/assign', 'Twilio\CallbackController@assignTask');
Route::post('/call/createTask/{id}', 'Twilio\EnqueueCallController@createTask');

Route::get('/workers/{id}', 'WorkersController@index');
Route::get('/call', 'WorkersController@call');
Route::get('/admin', 'AdminController@index');

//EMAIL
Route::get('/email', 'EmailController@index');
Route::put('/email/{id}', 'EmailController@update');

//CSAT
Route::post('/customer-satisfaction', 'CustomerSatisfactionsController@store');
Route::get('/customer-satisfactions/{id}', 'CustomerSatisfactionsController@index');

// Setting
Route::get('/systemsettings', 'SystemSettingsController@index');
Route::put('/systemsetting/{id}', 'SystemSettingsController@update');

// CALL RECORDS
Route::get('/callRecords', 'CallRecordsController@index');
Route::get('/callRecord/{recordSid}', 'CallRecordsController@getRecord');
Route::delete('/callRecord/{recordSid}', 'CallRecordsController@deleteRecord');

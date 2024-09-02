<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|php artisan config:clear
php artisan route:clear
php artisan cache:clear
*/

//Route::post('/categories', 'CategoryController@store');

Route::get('/about', 'SittingsController@about')->name('about');
Route::get('/', 'HomeController@index');//->name('home');
Route::get('/company_and_financial_year', 'HomeController@company_and_financial_year');//->name('home');

Route::get('/gl_index', 'ReportController@gl_index');
Route::get('/l_index', 'ReportController@l_index');
Route::get('/gl_exec', 'ReportController@exe_g_ledger');
Route::get('/l_exec', 'ReportController@exe_ledger');
Route::get('/tr_index', 'ReportController@tr_index');
Route::get('/tr_exec', 'ReportController@tr_exec');

//Route::get('/income_report', 'ReportController@income_report')->name('income_report');


Route::put('/update_state', 'CompanyController@update_state');

Route::get('/accounts_with_param/{acc_type}', [\App\Http\Controllers\AccountController::class, 'index'])->name('accounts_with_param');
//Route::get('/treasuary_trans_with_param/{trans_type}', [\App\Http\Controllers\TreasuryTransactionController::class, 'index'])->name('treasuary_trans_with_param');
Route::get('/treasury_transactions', 'TreasuryTransactionController@index')->name('treasury_transactions.index');
Route::get('/treasury_transactions_show_in/{id}', 'TreasuryTransactionController@show_in')->name('show_in');


Route::resource('/accounts', 'AccountController');
Route::resource('/categories', 'CategoryController');
Route::resource('/treasuries', 'TreasuryController');
Route::resource('/journals', 'JournalmController');
Route::resource('/journald', 'JournaldController');
Route::resource('/companies', 'CompanyController');
Route::resource('/financial_years', 'FinancialYearController');
Route::resource('/partners', 'PartnerController');
Route::resource('/sitting', 'SittingController');
Route::resource('/users', 'UserController');
Route::resource('/treasury_transaction', 'TreasuryTransactionController');
Route::resource('/treasury_transaction_details', 'TreasuryTransactionDetailController');

Route::get('treasury_transaction/{id}/print', [\App\Http\Controllers\TreasuryTransactionController::class, 'print'])->name('treasury_transaction.print');
Route::get('treasury_transaction__details_print/{id}/print', [\App\Http\Controllers\TreasuryTransactionDetailController::class, 'print'])->name('treasury_transaction__details_print');

Route::get('/journaldd/{id}', [JournalmController::class,'show']);
Route::get('/income_report', [ReportController::class, 'income_report']);
Route::get('/pulled_from_net_income_report', [ReportController::class, 'pulled_from_net_income_report']);
Route::get('/category_percentage_report', [\App\Http\Controllers\CategoryPercentageReportController::class, 'category_percentage_report']);
Route::get('/treasury_report', [ReportController::class, 'treasury_report']);
Route::get('/ledger2', [ReportController::class, 'ledger2']);
Route::get('/partners_accounts_report', [ReportController::class, 'partners_accounts_report']);
Route::get('/account_details_report', [ReportController::class, 'account_details_report']);

Auth::routes(['register' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

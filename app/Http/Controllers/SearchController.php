<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Company;
use App\Transaction;
use App\TransactionSubject;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Resources\TransactionSearch as SearchResource;

class SearchController extends Controller
{
    function search(Request $request) {
    	$client = Input::get('client', null);
    	$company = Input::get('company', null);
    	$subject = Input::get('subject', null);
    	if ($client && $subject) {
    		$transactions = DB::table('transactions')
    			->join('clients', 'transactions.client_id', '=', 'clients.id')
    			->join('companies', 'clients.company_id', '=', 'companies.id')
    			->join('transaction_subjects', 'transactions.subject_id', '=', 'transaction_subjects.id')
    			->where('client_id', '=', $client)
    			->where('subject_id', '=', $subject)
    			->get();
			return new SearchResource($transactions);
    	}
    	if ($company && !$client && !$subject) {
    		$transactions = DB::table('transactions')
    			->join('clients', 'transactions.client_id', '=', 'clients.id')
    			->join('companies', 'clients.company_id', '=', 'companies.id')
    			->join('transaction_subjects', 'transactions.subject_id', '=', 'transaction_subjects.id')
    			->where('clients.company_id', '=', $company)
    			->get();
			return new SearchResource($transactions);
    	}
    	if ($company && !$client && $subject) {
    		$transactions = DB::table('transactions')
    			->join('clients', 'transactions.client_id', '=', 'clients.id')
    			->join('companies', 'clients.company_id', '=', 'companies.id')
    			->join('transaction_subjects', 'transactions.subject_id', '=', 'transaction_subjects.id')
    			->where('clients.company_id', '=', $company)
    			->where('subject_id', '=', $subject)
    			->get();
			return new SearchResource($transactions);
    	}
    	if (!$company && $client && !$subject) {
    		$transactions = DB::table('transactions')
    			->join('clients', 'transactions.client_id', '=', 'clients.id')
    			->join('companies', 'clients.company_id', '=', 'companies.id')
    			->join('transaction_subjects', 'transactions.subject_id', '=', 'transaction_subjects.id')
    			->where('client_id', '=', $client)
    			->get();
			return new SearchResource($transactions);
    	}
    	if (!$company && !$client && $subject) {
    		$transactions = DB::table('transactions')
    			->join('clients', 'transactions.client_id', '=', 'clients.id')
    			->join('companies', 'clients.company_id', '=', 'companies.id')
    			->join('transaction_subjects', 'transactions.subject_id', '=', 'transaction_subjects.id')
    			->where('subject_id', '=', $subject)
    			->get()->groupBy('subject_id');
			return new SearchResource($transactions);
    	}
    }
}

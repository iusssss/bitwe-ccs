<?php

namespace App\Exports;

use App\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class ClientsExport implements FromCollection, WithHeadings, WithEvents
{
	private $company_id;
	public function __construct(int $company_id)
	{
		$this->company_id = $company_id;
	}

	public function registerEvents(): array
    {
        $styleArray = [
        	'font' => [
        		'bold' => true,
        	]
        ];

        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) use ($styleArray) {
            	$event->sheet->getStyle('A1:D1')->applyFromArray($styleArray);
            },
        ];
    }

    public function collection()
    {
        return DB::table('clients as c')
   				->select(DB::raw('c.fullname, c.email, c.contactNumber, cp.name'))
    			->join('companies as cp', 'cp.id', '=', 'c.company_id')
    			->where('cp.id', '=', $this->company_id)
    			->get();
    }

    public function headings(): array
    {
    	return [
    		'Name',
    		'Email',	
    		'Contact #',
    		'Company'
    	];
    }
}

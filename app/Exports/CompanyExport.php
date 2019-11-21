<?php

namespace App\Exports;

use App\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class CompanyExport implements FromCollection, WithHeadings, WithEvents
{
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
        return Company::select('name', 'address', 'email', 'contact_no')->get();
    }

    public function headings(): array
    {
    	return [
    		'Name',
    		'Address',
    		'Email',
    		'Contact #'
    	];
    }
}

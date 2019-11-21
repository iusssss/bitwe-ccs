<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersExport implements FromCollection, WithHeadings, WithEvents
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
        return User::select('name', 'email', 'contact_no', 'privilege')->get();
    }

    public function headings(): array
    {
    	return [
    		'Name',
    		'Email',
    		'Contact #',
    		'Privilege',
    	];
    }
}

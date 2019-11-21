<?php

namespace App\Exports;

use App\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ServicesExport implements FromCollection, WithHeadings, WithEvents
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
        return Service::select('name', 'description')->get();
    }

    public function headings(): array
    {
    	return [
    		'Name',
    		'Description',
    	];
    }
}

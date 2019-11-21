<?php

namespace App\Exports;

use App\Ticket;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TicketsExport implements FromCollection, WithHeadings, WithEvents, WithMapping, ShouldAutoSize
{
    private $filter;
    private $filter_text;
    public function __construct(int $filter)
    {
        $this->filter = $filter;
        if ($filter == 0)
            $this->filter_text = "THIS YEAR";
        else if ($filter == 1)
            $this->filter_text = "THIS MONTH";
        else if ($filter == 2)
            $this->filter_text = "THIS WEEK";
        else if ($filter == 3)
            $this->filter_text = "TODAY";
    }
	public function registerEvents(): array
    {
        $styleArray = [
        	'font' => [
        		'bold' => true,
        		'color' => [ 'rgb' => 'ffffff']
        	],
    		'fill' => [ 
    			'fillType' => Fill::FILL_SOLID, 
    			'rotation' => 0, 
    			'startColor' => [ 'rgb' => '305496' ],
    		],
    		'alignment' => [ 
    			'horizontal' => Alignment::HORIZONTAL_CENTER, 
    			'vertical' => Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE 
    		],
    		'border' => [
    			'borderStyle' => Border::BORDER_THIN, 
    			'color' => [ 'rgb' => '00000' ] 
    		]
        ];
        $cellStyleArray = [
    		'alignment' => [ 
    			'horizontal' => Alignment::HORIZONTAL_LEFT, 
    			'vertical' => Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE 
    		],
		    'borders' => [
		        'allBorders' => [
		            'borderStyle' => Border::BORDER_THIN,
		            'color' => ['argb' => '000000'],
		        ],
		    ],
        ];

        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) use ($styleArray, $cellStyleArray) {
        		$event->sheet->insertNewRowBefore(1);
        		$event->sheet->insertNewColumnBefore('A',1);
                $event->sheet->setCellValue('B1', "TICKETS $this->filter_text");
                $event->sheet->getStyle('B1')->getFont()->setBold(true);
                $event->sheet->getStyle('B1')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('B2:G2')->getFont()->setSize(12);
            	$highestRow = $event->sheet->getHighestDataRow();
            	$event->sheet->getStyle('B2:G2')->applyFromArray($styleArray);
            	$event->sheet->getStyle('B3:G'.$highestRow)->applyFromArray($cellStyleArray);
            },
        ];
    }
    public function collection()
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);
        if ($this->filter == 0)
            return Ticket::with('client', 'service')
                ->where('client_id', "<>", "null")
                ->whereYear('created_at', date('Y'))
                ->get();
        else if ($this->filter == 1)
            return Ticket::with('client', 'service')
                ->where('client_id', "<>", "null")
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->get();
        else if ($this->filter == 2)
            return Ticket::with('client', 'service')
                ->where('client_id', "<>", "null")
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->get();
        else if ($this->filter == 3)
            return Ticket::with('client', 'service')
                ->where('client_id', "<>", "null")
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->whereDay('created_at', date('d'))
                ->get();
    // 	return DB::table('tickets as t')
				// ->select(DB::raw('t.ticketId, t.created_at, cm.name, s.name, t.type, tp.created_at'))
				// ->join('clients as c', 'c.id', '=', 't.client_id')
				// ->join('companies as cm', 'cm.id', '=', 'c.company_id')
				// ->join('services as s', 's.id', '=', 't.service_id')
				// ->join(DB::raw('(
				// 	select ticket_id, status, created_at
				// 	from ticket_updates
				// 	order by created_at DESC
				// 	) tp'), function ($join) {
				// 	$join->on('tp.ticket_id', '=', 't.ticketId');
				// })
				// ->groupBy('t.ticketId')
				// ->get();
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticketId,
            $ticket->created_at->format('M. d, Y H:i:s'),
            $ticket->client->company->name,
            $ticket->service->name,
            $ticket->type,
            count($ticket->resolved) > 0 ? $ticket->resolved[0]->created_at->format('M. d, Y H:i:s') : null
            // Date::dateTimeToExcel($ticket->created_at),
        ];
    }

    public function headings(): array
    {
    	return [
    		'Ticket #',
    		'Date & Time of Ticket Creation',
    		'Company Name',
    		'Type of Service',
    		'Incident / Request',
    		'Date & Time Resolved'
    	];
    }
}

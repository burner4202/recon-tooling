<?php

namespace Vanguard\Exports;

use Vanguard\KnownStructures;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StructuresExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    
    
    public function collection()
    {
    	return KnownStructures::get();
    }

    public function headings(): array
    {
    	return [
    		'#',
    		'Structure Hash',
    		'Structure System ID',
    		'Structure Type ID',
    		'Structure Name',
    		'Structure Type',
    		'Distance from When Scanned',
    		'Created At',
    		'Updated At',
    		'System',
    		'Structure ID',
    		'Owner Corporation ID',
    		'Owner Corporation Name',
    		'Fitting JSON',
    		'Fitting Value',
    		'Structure State',
    		'Structure Destroyed',
    		'Added By User ID',
    		'Added By Username',
    		'Updated By User ID',
    		'Updated By User Name',
    		'Vulnerability Hour',
    		'Vulnerability Day',
    		'Owner Alliance ID',
    		'Owner Alliance Name',
    		'Structure Status',
    		'Structure Fitted',
    		'Alliance TICKER',
    		'Region ID',
    		'Region Name',
    		'Constellation ID',
    		'Constellation Name',
    	];
    }

  
    public function registerEvents(): array
    {
    	return [
    		AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:AE1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

            },
        ];
    }
    



}



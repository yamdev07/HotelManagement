<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AvailabilityExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping, ShouldAutoSize
{
    protected $data;
    protected $period;
    protected $startDate;
    protected $endDate;
    
    public function __construct($data, $period = null, $startDate = null, $endDate = null)
    {
        $this->data = collect($data);
        $this->period = $period;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    
    public function collection()
    {
        return $this->data;
    }
    
    public function headings(): array
    {
        $periodLabel = $this->getPeriodLabel();
        
        return [
            ['Rapport de disponibilité des chambres'],
            ['Période : ' . $periodLabel],
            ['Date de génération : ' . now()->format('d/m/Y H:i')],
            [], // Ligne vide
            [
                'Chambre',
                'Type',
                'Statut',
                'Prix/nuit (CFA)',
                'Jours occupés',
                'Revenu total (CFA)',
                'Taux d\'occupation (%)',
                'Disponibilité'
            ]
        ];
    }
    
    public function map($row): array
    {
        return [
            $row['Chambre'] ?? '',
            $row['Type'] ?? 'N/A',
            $row['Statut'] ?? 'N/A',
            number_format($row['Prix/nuit'] ?? 0, 0, ',', ' '),
            $row['Jours occupés'] ?? 0,
            number_format($row['Revenu total'] ?? 0, 0, ',', ' '),
            $row['Taux occupation'] ?? '0%',
            $row['Disponibilité'] ?? 'N/A'
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Chambre
            'B' => 20, // Type
            'C' => 20, // Statut
            'D' => 20, // Prix/nuit
            'E' => 15, // Jours occupés
            'F' => 20, // Revenu total
            'G' => 20, // Taux occupation
            'H' => 15, // Disponibilité
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Fusionner les 3 premières lignes pour le titre
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');
        
        // Appliquer les styles
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50']
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ]
        ]);
        
        $sheet->getStyle('A2:H2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '2C3E50']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECF0F1']
            ],
            'alignment' => [
                'horizontal' => 'center'
            ]
        ]);
        
        $sheet->getStyle('A3:H3')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => '7F8C8D']
            ],
            'alignment' => [
                'horizontal' => 'center'
            ]
        ]);
        
        // Style pour les en-têtes de colonne (ligne 5)
        $sheet->getStyle('A5:H5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3498DB']
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '2C3E50']
                ]
            ]
        ]);
        
        // Style pour les données
        $lastRow = $this->data->count() + 5; // +5 pour les lignes d'en-tête
        $sheet->getStyle('A6:H' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'BDC3C7']
                ]
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ]
        ]);
        
        // Style alterné pour les lignes
        for ($i = 6; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':H' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ]);
            }
        }
        
        // Formater les colonnes numériques
        $sheet->getStyle('D6:F' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        
        // Ajuster la hauteur des lignes
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(5)->setRowHeight(25);
        
        // Ajouter un total à la fin
        $totalRow = $lastRow + 2;
        $sheet->setCellValue('E' . $totalRow, 'TOTAUX :');
        $sheet->setCellValue('F' . $totalRow, '=SUM(F6:F' . $lastRow . ')');
        $sheet->getStyle('E' . $totalRow . ':F' . $totalRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2ECC71']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '27AE60']
                ]
            ]
        ]);
        
        // Formater le total
        $sheet->getStyle('F' . $totalRow)->getNumberFormat()->setFormatCode('#,##0 "CFA"');
        
        return [];
    }
    
    private function getPeriodLabel(): string
    {
        if ($this->period === 'today') {
            return 'Aujourd\'hui - ' . now()->format('d/m/Y');
        } elseif ($this->period === 'week') {
            $start = now()->startOfWeek()->format('d/m/Y');
            $end = now()->endOfWeek()->format('d/m/Y');
            return 'Semaine en cours - Du ' . $start . ' au ' . $end;
        } elseif ($this->period === 'month') {
            $start = now()->startOfMonth()->format('d/m/Y');
            $end = now()->endOfMonth()->format('d/m/Y');
            return 'Mois en cours - Du ' . $start . ' au ' . $end;
        } elseif ($this->period === 'custom' && $this->startDate && $this->endDate) {
            $start = \Carbon\Carbon::parse($this->startDate)->format('d/m/Y');
            $end = \Carbon\Carbon::parse($this->endDate)->format('d/m/Y');
            return 'Personnalisée - Du ' . $start . ' au ' . $end;
        }
        
        return 'Non spécifiée';
    }
}
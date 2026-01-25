<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class CalendarExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping, ShouldAutoSize, WithEvents
{
    protected $calendarData;
    protected $dates;
    protected $month;
    protected $year;
    
    public function __construct($calendarData, $dates, $month, $year)
    {
        $this->calendarData = $calendarData;
        $this->dates = $dates;
        $this->month = $month;
        $this->year = $year;
    }
    
    public function collection()
    {
        $data = [];
        
        foreach ($this->calendarData as $roomData) {
            $room = $roomData['room'];
            $row = [
                'Chambre' => $room->number,
                'Type' => $room->type->name ?? 'N/A',
                'Capacité' => $room->capacity,
                'Prix/nuit' => $room->price . ' CFA',
                'Statut' => $room->roomStatus->name ?? 'N/A'
            ];
            
            // Ajouter les jours du mois
            foreach ($this->dates as $dateString => $dateInfo) {
                $availability = $roomData['availability'][$dateString] ?? null;
                if ($availability) {
                    $status = $availability['occupied'] ? 'O' : ($availability['css_class'] === 'unavailable' ? 'I' : 'D');
                    $row[$dateInfo['day_number']] = $status;
                }
            }
            
            $data[] = $row;
        }
        
        return collect($data);
    }
    
    public function headings(): array
    {
        $headings = ['Chambre', 'Type', 'Capacité', 'Prix/nuit', 'Statut'];
        
        // Ajouter les jours comme en-têtes
        foreach ($this->dates as $dateInfo) {
            $headings[] = $dateInfo['day_number'] . "\n" . $dateInfo['day_name'];
        }
        
        return $headings;
    }
    
    public function map($row): array
    {
        $mapped = [
            $row['Chambre'] ?? '',
            $row['Type'] ?? 'N/A',
            $row['Capacité'] ?? 0,
            $row['Prix/nuit'] ?? '0 CFA',
            $row['Statut'] ?? 'N/A'
        ];
        
        // Ajouter les valeurs des jours
        foreach ($this->dates as $dateString => $dateInfo) {
            $dayNum = $dateInfo['day_number'];
            $mapped[] = $row[$dayNum] ?? '';
        }
        
        return $mapped;
    }
    
    public function columnWidths(): array
    {
        $widths = [
            'A' => 15, // Chambre
            'B' => 20, // Type
            'C' => 12, // Capacité
            'D' => 15, // Prix/nuit
            'E' => 15, // Statut
        ];
        
        // Largeur pour les colonnes de jours
        $dayCount = count($this->dates);
        for ($i = 0; $i < $dayCount; $i++) {
            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $i);
            $widths[$column] = 8;
        }
        
        return $widths;
    }
    
    public function styles(Worksheet $sheet)
    {
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + count($this->dates));
        $lastRow = count($this->calendarData) + 1; // +1 pour l'en-tête
        
        // Titre
        $sheet->mergeCells('A1:' . $lastColumn . '1');
        $sheet->setCellValue('A1', 'Calendrier de disponibilité - ' . $this->getMonthName($this->month) . ' ' . $this->year);
        $sheet->getStyle('A1')->applyFromArray([
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
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // Sous-titre
        $sheet->mergeCells('A2:' . $lastColumn . '2');
        $sheet->setCellValue('A2', 'Légende: O = Occupée, D = Disponible, I = Indisponible, M = Maintenance, N = Nettoyage');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'size' => 10,
                'italic' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
        
        // Date de génération
        $sheet->mergeCells('A3:' . $lastColumn . '3');
        $sheet->setCellValue('A3', 'Généré le ' . now()->format('d/m/Y H:i'));
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'size' => 9,
                'color' => ['rgb' => '7F8C8D']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
        
        // Décaler les données pour tenir compte des lignes de titre
        $dataStartRow = 4;
        
        // En-têtes de colonnes (ligne 4)
        $headerRow = $dataStartRow;
        $sheet->getStyle('A' . $headerRow . ':' . $lastColumn . $headerRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3498DB']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '2C3E50']
                ]
            ]
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(40);
        
        // Données
        $dataFirstRow = $dataStartRow + 1;
        $dataLastRow = $dataStartRow + count($this->calendarData);
        $sheet->getStyle('A' . $dataFirstRow . ':' . $lastColumn . $dataLastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'BDC3C7']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        // Style alterné pour les lignes
        for ($row = $dataFirstRow; $row <= $dataLastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ]);
            }
        }
        
        // Style conditionnel pour les jours
        $firstDayColumn = 'F'; // Colonne du premier jour
        $lastDayColumn = $lastColumn;
        
        for ($col = $firstDayColumn; $col <= $lastDayColumn; $col = $this->nextColumn($col)) {
            $range = $col . $dataFirstRow . ':' . $col . $dataLastRow;
            
            // Style conditionnel - nous le gérerons dans registerEvents()
        }
        
        // Ajuster la hauteur des lignes de données
        for ($row = $dataFirstRow; $row <= $dataLastRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(25);
        }
        
        // Ajouter une ligne de statistiques
        $statsRow = $dataLastRow + 2;
        $sheet->setCellValue('A' . $statsRow, 'STATISTIQUES DU MOIS');
        $sheet->getStyle('A' . $statsRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '2C3E50']
            ]
        ]);
        
        $statsRow++;
        $sheet->setCellValue('A' . $statsRow, 'Chambres totales:');
        $sheet->setCellValue('B' . $statsRow, count($this->calendarData));
        
        $statsRow++;
        $sheet->setCellValue('A' . $statsRow, 'Taux d\'occupation moyen:');
        $sheet->setCellValue('B' . $statsRow, $this->calculateAverageOccupancy() . '%');
        
        $statsRow++;
        $sheet->setCellValue('A' . $statsRow, 'Revenu estimé:');
        $sheet->setCellValue('B' . $statsRow, number_format($this->calculateEstimatedRevenue(), 0, ',', ' ') . ' CFA');
        
        return [];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Appliquer le style conditionnel pour les jours
                $firstDayColumn = 'F';
                $lastDayColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + count($this->dates));
                $dataStartRow = 5; // Ligne où commencent les données
                $dataLastRow = 4 + count($this->calendarData);
                
                for ($col = $firstDayColumn; $col <= $lastDayColumn; $col = $this->nextColumn($col)) {
                    for ($row = $dataStartRow; $row <= $dataLastRow; $row++) {
                        $cell = $sheet->getCell($col . $row);
                        $value = $cell->getValue();
                        
                        $style = [
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF']
                            ]
                        ];
                        
                        switch ($value) {
                            case 'O': // Occupée
                                $style['fill']['startColor'] = ['rgb' => 'E74C3C']; // Rouge
                                break;
                            case 'D': // Disponible
                                $style['fill']['startColor'] = ['rgb' => '2ECC71']; // Vert
                                break;
                            case 'I': // Indisponible
                                $style['fill']['startColor'] = ['rgb' => '7F8C8D']; // Gris
                                break;
                            case 'M': // Maintenance
                                $style['fill']['startColor'] = ['rgb' => 'E67E22']; // Orange
                                break;
                            case 'N': // Nettoyage
                                $style['fill']['startColor'] = ['rgb' => '3498DB']; // Bleu
                                break;
                            default:
                                continue 2; // Passer à la cellule suivante
                        }
                        
                        $sheet->getStyle($col . $row)->applyFromArray($style);
                    }
                }
            }
        ];
    }
    
    private function nextColumn($column)
    {
        return ++$column;
    }
    
    private function getMonthName($month)
    {
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];
        
        return $months[$month] ?? 'Mois';
    }
    
    private function calculateAverageOccupancy()
    {
        $totalOccupiedDays = 0;
        $totalPossibleDays = 0;
        
        foreach ($this->calendarData as $roomData) {
            foreach ($this->dates as $dateString => $dateInfo) {
                $availability = $roomData['availability'][$dateString] ?? null;
                if ($availability) {
                    if ($availability['occupied']) {
                        $totalOccupiedDays++;
                    }
                    $totalPossibleDays++;
                }
            }
        }
        
        return $totalPossibleDays > 0 ? round(($totalOccupiedDays / $totalPossibleDays) * 100, 1) : 0;
    }
    
    private function calculateEstimatedRevenue()
    {
        $revenue = 0;
        
        foreach ($this->calendarData as $roomData) {
            $room = $roomData['room'];
            foreach ($this->dates as $dateString => $dateInfo) {
                $availability = $roomData['availability'][$dateString] ?? null;
                if ($availability && $availability['occupied']) {
                    $revenue += $room->price;
                }
            }
        }
        
        return $revenue;
    }
}
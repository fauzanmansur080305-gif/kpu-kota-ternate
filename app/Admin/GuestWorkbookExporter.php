<?php

namespace App\Admin;

use App\Models\Tamu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use ZipArchive;

class GuestWorkbookExporter
{
    /**
     * @param  iterable<Tamu>  $tamus
     */
    public function export(iterable $tamus): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekspor Excel memerlukan ekstensi PHP ZipArchive.');
        }

        $path = tempnam(sys_get_temp_dir(), 'kpu-report-');

        if ($path === false) {
            throw new RuntimeException('File sementara untuk ekspor Excel gagal dibuat.');
        }

        $archive = new ZipArchive;
        $opened = $archive->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($opened !== true) {
            @unlink($path);

            throw new RuntimeException('File ekspor Excel gagal dibuat.');
        }

        try {
            $rows = [];
            $media = [];
            $imageRelationships = [];
            $rowNumber = 3;
            $imageNumber = 1;

            foreach ($tamus as $tamu) {
                $documentationPath = $tamu->documentationPath();
                $images = [
                    [
                        'imageData' => $this->signatureBytes($tamu->paraf),
                        'column' => 8,
                    ],
                    [
                        'imageData' => $this->storedImageBytes($documentationPath),
                        'column' => 9,
                    ],
                ];

                $rows[] = $this->row($rowNumber, [
                    $tamu->hari_tanggal,
                    $tamu->jam,
                    $tamu->nama,
                    $tamu->instansi,
                    $tamu->alamat,
                    $tamu->keperluan,
                    $tamu->tujuan,
                    $tamu->no_handphone,
                    '',
                    $images[1]['imageData'] !== null
                        ? 'Terlampir'
                        : ($documentationPath === null ? 'Tidak tersedia' : 'File tidak tersedia di penyimpanan'),
                ]);

                foreach ($images as $image) {
                    $imageData = $image['imageData'];
                    if ($imageData === null) {
                        continue;
                    }

                    $extension = $imageData['mime'] === 'image/jpeg' ? 'jpeg' : 'png';
                    $mediaPath = "xl/media/image{$imageNumber}.{$extension}";
                    $relationshipId = "rId{$imageNumber}";

                    if (! $archive->addFromString($mediaPath, $imageData['bytes'])) {
                        throw new RuntimeException("Gambar ekspor gagal ditulis ke {$mediaPath}.");
                    }
                    $imageRelationships[] = '<Relationship Id="'.$relationshipId.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="../media/image'.$imageNumber.'.'.$extension.'"/>';
                    $media[] = [
                        'column' => $image['column'],
                        'row' => $rowNumber - 1,
                        'relationshipId' => $relationshipId,
                        'name' => "Gambar {$imageNumber}",
                    ];
                    $imageNumber++;
                }

                $rowNumber++;
            }

            $lastRow = max(2, $rowNumber - 1);
            $worksheet = $this->worksheetXml($rows, $lastRow, $media !== []);
            $files = [
                '[Content_Types].xml' => $this->contentTypesXml($media !== []),
                '_rels/.rels' => $this->rootRelationshipsXml(),
                'xl/workbook.xml' => $this->workbookXml(),
                'xl/_rels/workbook.xml.rels' => $this->workbookRelationshipsXml(),
                'xl/styles.xml' => $this->stylesXml(),
                'xl/worksheets/sheet1.xml' => $worksheet,
            ];

            if ($media !== []) {
                $files['xl/worksheets/_rels/sheet1.xml.rels'] = $this->worksheetRelationshipsXml();
                $files['xl/drawings/drawing1.xml'] = $this->drawingXml($media);
                $files['xl/drawings/_rels/drawing1.xml.rels'] = $this->drawingRelationshipsXml($imageRelationships);
            }

            foreach ($files as $file => $contents) {
                if (! $archive->addFromString($file, $contents)) {
                    throw new RuntimeException("File ekspor Excel gagal menulis {$file}.");
                }
            }

            if (! $archive->close()) {
                throw new RuntimeException('File ekspor Excel gagal diselesaikan.');
            }
        } catch (\Throwable $exception) {
            $archive->close();
            @unlink($path);

            throw $exception;
        }

        return $path;
    }

    /**
     * @param  array<int, string>  $values
     */
    private function row(int $rowNumber, array $values): string
    {
        $xml = '<row r="'.$rowNumber.'" ht="68" customHeight="1">';

        foreach ($values as $column => $value) {
            $xml .= $this->cell($this->columnName($column + 1).$rowNumber, $value, 3);
        }

        return $xml.'</row>';
    }

    private function cell(string $reference, string $value, int $style): string
    {
        return '<c r="'.$reference.'" s="'.$style.'" t="inlineStr"><is><t xml:space="preserve">'
            .$this->xml($value)
            .'</t></is></c>';
    }

    private function worksheetXml(array $rows, int $lastRow, bool $hasImages): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<dimension ref="A1:J'.$lastRow.'"/>'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="2" topLeftCell="A3" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="21"/>'
            .'<cols>'
            .'<col min="1" max="2" width="15" customWidth="1"/>'
            .'<col min="3" max="4" width="24" customWidth="1"/>'
            .'<col min="5" max="6" width="36" customWidth="1"/>'
            .'<col min="7" max="8" width="22" customWidth="1"/>'
            .'<col min="9" max="10" width="20" customWidth="1"/>'
            .'</cols><sheetData>'
            .'<row r="1" ht="32" customHeight="1">'.$this->cell('A1', 'LAPORAN DATA TAMU - KPU KOTA TERNATE', 1).'</row>'
            .'<row r="2" ht="26" customHeight="1">';

        foreach (['Tanggal', 'Jam', 'Nama Tamu', 'Instansi', 'Alamat', 'Keperluan', 'Tujuan', 'Nomor Handphone', 'Paraf', 'Dokumentasi'] as $index => $heading) {
            $xml .= $this->cell($this->columnName($index + 1).'2', $heading, 2);
        }

        $xml .= '</row>'.implode('', $rows).'</sheetData>'
            .'<autoFilter ref="A2:J'.$lastRow.'"/>'
            .'<mergeCells count="1"><mergeCell ref="A1:J1"/></mergeCells>';

        if ($hasImages) {
            $xml .= '<drawing r:id="rId1"/>';
        }

        return $xml.'</worksheet>';
    }

    private function signatureBytes(?string $signature): ?array
    {
        if (! $signature || preg_match('/^data:image\/(png|jpeg);base64,([A-Za-z0-9+\/]+={0,2})$/', $signature, $matches) !== 1) {
            return null;
        }

        $bytes = base64_decode($matches[2], true);

        if ($bytes === false) {
            return null;
        }

        $image = getimagesizefromstring($bytes);

        if (! $image || ! in_array($image['mime'], ['image/png', 'image/jpeg'], true)) {
            return null;
        }

        return ['bytes' => $bytes, 'mime' => $image['mime']];
    }

    private function storedImageBytes(?string $path): ?array
    {
        if (! $path) {
            return null;
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($path)) {
            Log::warning('Guest workbook export skipped a missing documentation image.', [
                'path' => $path,
            ]);

            return null;
        }

        $bytes = $disk->get($path);

        if (! is_string($bytes)) {
            throw new RuntimeException("Gambar dokumentasi {$path} gagal dibaca.");
        }
        $image = getimagesizefromstring($bytes);

        if (! $image || ! in_array($image['mime'], ['image/png', 'image/jpeg'], true)) {
            throw new RuntimeException("Gambar dokumentasi {$path} bukan PNG atau JPEG yang valid.");
        }

        return ['bytes' => $bytes, 'mime' => $image['mime']];
    }

    /**
     * @param  array<int, array{column: int, row: int, relationshipId: string, name: string}>  $media
     */
    private function drawingXml(array $media): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<xdr:wsDr xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';

        foreach ($media as $index => $image) {
            $startColumn = $image['column'];
            $xml .= '<xdr:twoCellAnchor editAs="oneCell">'
                .'<xdr:from><xdr:col>'.$startColumn.'</xdr:col><xdr:colOff>38000</xdr:colOff><xdr:row>'.$image['row'].'</xdr:row><xdr:rowOff>12000</xdr:rowOff></xdr:from>'
                .'<xdr:to><xdr:col>'.($startColumn + 1).'</xdr:col><xdr:colOff>0</xdr:colOff><xdr:row>'.($image['row'] + 1).'</xdr:row><xdr:rowOff>0</xdr:rowOff></xdr:to>'
                .'<xdr:pic><xdr:nvPicPr><xdr:cNvPr id="'.($index + 1).'" name="'.$this->xml($image['name']).'"/><xdr:cNvPicPr><a:picLocks noChangeAspect="1"/></xdr:cNvPicPr></xdr:nvPicPr>'
                .'<xdr:blipFill><a:blip r:embed="'.$image['relationshipId'].'"/><a:stretch><a:fillRect/></a:stretch></xdr:blipFill>'
                .'<xdr:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="1047750" cy="666750"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></xdr:spPr></xdr:pic><xdr:clientData/></xdr:twoCellAnchor>';
        }

        return $xml.'</xdr:wsDr>';
    }

    private function drawingRelationshipsXml(array $relationships): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .implode('', $relationships)
            .'</Relationships>';
    }

    private function contentTypesXml(bool $hasImages): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Default Extension="png" ContentType="image/png"/>'
            .'<Default Extension="jpeg" ContentType="image/jpeg"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';

        if ($hasImages) {
            $xml .= '<Override PartName="/xl/drawings/drawing1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml"/>';
        }

        return $xml.'</Types>';
    }

    private function rootRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Data Tamu" sheetId="1" r:id="rId1"/></sheets></workbook>';
    }

    private function workbookRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
    }

    private function worksheetRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" Target="../drawings/drawing1.xml"/>'
            .'</Relationships>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<fonts count="3"><font><sz val="11"/><name val="Arial"/></font><font><b/><sz val="15"/><color rgb="FF4A1E0E"/><name val="Arial"/></font><font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Arial"/></font></fonts>'
            .'<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF4A1E0E"/><bgColor indexed="64"/></patternFill></fill></fills>'
            .'<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border><border><left style="thin"><color rgb="FFE5EBF2"/></left><right style="thin"><color rgb="FFE5EBF2"/></right><top style="thin"><color rgb="FFE5EBF2"/></top><bottom style="thin"><color rgb="FFE5EBF2"/></bottom><diagonal/></border></borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="4"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment vertical="center"/></xf><xf numFmtId="0" fontId="2" fillId="2" borderId="0" xfId="0" applyAlignment="1"><alignment vertical="center" horizontal="center" wrapText="1"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyAlignment="1"><alignment vertical="center" wrapText="1"/></xf></cellXfs>'
            .'<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles></styleSheet>';
    }

    private function columnName(int $number): string
    {
        $name = '';

        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $name = chr(65 + $remainder).$name;
            $number = intdiv($number - 1, 26);
        }

        return $name;
    }

    private function xml(string $value): string
    {
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value) ?? '';

        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}

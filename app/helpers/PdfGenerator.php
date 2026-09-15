<?php

namespace App\Helpers;

class PdfGenerator
{
    private $x = 0;
    private $y = 0;
    private $marginLeft = 20;
    private $marginTop = 20;
    private $pageWidth = 210;
    private $pageHeight = 297;
    private $currentFont = 'Helvetica';
    private $currentSize = 10;
    private $currentStyle = '';
    private $textR = 0;
    private $images = [];
    private $textG = 0;
    private $textB = 0;
    private $title = 'Document';
    private $pages = [];
    private $currentPage = -1;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function addPage()
    {
        $this->currentPage = count($this->pages);
        $this->pages[] = [];
        $this->x = $this->marginLeft;
        $this->y = $this->marginTop;
    }

    public function setFont($family, $style = '', $size = 10)
    {
        $this->currentFont = $family;
        $this->currentStyle = $style;
        $this->currentSize = $size;
    }

    public function setXY($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function setTextColor($r, $g, $b)
    {
        $this->textR = $r;
        $this->textG = $g;
        $this->textB = $b;
    }

    public function cell($w, $h, $txt, $border = 0, $ln = 0, $align = 'L')
    {
        if ($this->currentPage < 0) return;
        $page = &$this->pages[$this->currentPage];
        if ($w == 0) $w = $this->pageWidth - $this->x - $this->marginLeft;

        $page[] = [
            'type' => 'cell',
            'x' => $this->x,
            'y' => $this->y,
            'w' => $w,
            'h' => $h,
            'txt' => $txt,
            'font' => $this->currentFont,
            'size' => $this->currentSize,
            'style' => $this->currentStyle,
            'align' => $align,
            'border' => $border,
            'tr' => $this->textR,
            'tg' => $this->textG,
            'tb' => $this->textB
        ];

        if ($ln) {
            $this->x = $this->marginLeft;
            $this->y += $h;
        } else {
            $this->x += $w;
        }
    }

    public function ln($h = 5)
    {
        $this->x = $this->marginLeft;
        $this->y += $h;
    }

    public function line($x1, $y1, $x2, $y2)
    {
        if ($this->currentPage < 0) return;
        $this->pages[$this->currentPage][] = [
            'type' => 'line',
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2,
            'y2' => $y2
        ];
    }

    public function rect($x, $y, $w, $h)
    {
        if ($this->currentPage < 0) return;
        $this->pages[$this->currentPage][] = [
            'type' => 'rect',
            'x' => $x,
            'y' => $y,
            'w' => $w,
            'h' => $h,
            'fill' => false
        ];
    }

    public function filledRect($x, $y, $w, $h, $r = 0, $g = 0, $b = 0)
    {
        if ($this->currentPage < 0) return;
        $this->pages[$this->currentPage][] = [
            'type' => 'rect',
            'x' => $x,
            'y' => $y,
            'w' => $w,
            'h' => $h,
            'fill' => true,
            'fr' => $r,
            'fg' => $g,
            'fb' => $b
        ];
    }

    public function getY()
    {
        return $this->y;
    }

    public function getPageHeight()
    {
        return $this->pageHeight;
    }

    public function getPageWidth()
    {
        return $this->pageWidth;
    }

    public function numberToWords($num)
    {
        $num = (int)$num;
        if ($num == 0) return 'Zero';
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        if ($num < 20) return $ones[$num];
        if ($num < 100) return trim($tens[intval($num / 10)] . ' ' . $ones[$num % 10]);

        $words = [];
        $crore = intval($num / 10000000);
        if ($crore > 0) {
            $words[] = $this->numberToWords($crore) . ' Crore';
            $num %= 10000000;
        }
        $lakh = intval($num / 100000);
        if ($lakh > 0) {
            $words[] = $this->numberToWords($lakh) . ' Lakh';
            $num %= 100000;
        }
        $thousand = intval($num / 1000);
        if ($thousand > 0) {
            $words[] = $this->numberToWords($thousand) . ' Thousand';
            $num %= 1000;
        }
        $hundred = intval($num / 100);
        if ($hundred > 0) {
            $words[] = $ones[$hundred] . ' Hundred';
            $num %= 100;
        }
        if ($num > 0) {
            $words[] = $num < 20 ? $ones[$num] : trim($tens[intval($num / 10)] . ' ' . $ones[$num % 10]);
        }
        return implode(' ', $words);
    }

    public function image($file, $x, $y, $w, $h)
    {
        if ($this->currentPage < 0 || !file_exists($file)) return;
        $info = @getimagesize($file);
        if (!$info) return;
        $id = 'img' . count($this->images);
        $type = $info[2];
        if ($type === IMAGETYPE_JPEG) {
            $data = @file_get_contents($file);
            if ($data === false) return;
            $this->images[$id] = ['data' => $data, 'type' => 'jpeg', 'w' => $info[0], 'h' => $info[1]];
        } elseif ($type === IMAGETYPE_PNG) {
            $im = @imagecreatefrompng($file);
            if (!$im) {
                $im = @imagecreatefromstring(@file_get_contents($file));
                if (!$im) return;
            }
            $iw = imagesx($im);
            $ih = imagesy($im);
            $rgb = '';
            $trueColor = imageistruecolor($im);
            for ($py = 0; $py < $ih; $py++) {
                for ($px = 0; $px < $iw; $px++) {
                    if ($trueColor) {
                        $c = imagecolorsforindex($im, imagecolorat($im, $px, $py));
                        $rgb .= chr($c['red']) . chr($c['green']) . chr($c['blue']);
                    } else {
                        $idx = imagecolorat($im, $px, $py);
                        $rgb .= chr(($idx >> 16) & 0xFF) . chr(($idx >> 8) & 0xFF) . chr($idx & 0xFF);
                    }
                }
            }
            imagedestroy($im);
            $this->images[$id] = ['data' => $rgb, 'type' => 'png', 'w' => $iw, 'h' => $ih];
        } else {
            return;
        }
        $this->pages[$this->currentPage][] = [
            'type' => 'image',
            'x' => $x,
            'y' => $y,
            'w' => $w,
            'h' => $h,
            'id' => $id,
            'dpi' => 72
        ];
    }

    public function output($filename)
    {
        $pdf = $this->buildPdf();
        if (PHP_SAPI !== 'cli') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
        }
        echo $pdf;
        return $pdf;
    }

    private function mmToPt($mm)
    {
        return $mm * 2.83465;
    }

    private function buildPdf()
    {
        $pageW = $this->mmToPt($this->pageWidth);
        $pageH = $this->mmToPt($this->pageHeight);

        $pdf = "%PDF-1.4\n";
        $offsets = [];
        $objId = 1;

        $offsets[1] = strlen($pdf);
        $pdf .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $objId = 2;

        $kids = '';
        for ($i = 0; $i < count($this->pages); $i++) {
            $kids .= ($i + 3) . ' 0 R ';
        }
        $offsets[2] = strlen($pdf);
        $pdf .= "2 0 obj\n<< /Type /Pages /Kids [" . trim($kids) . "] /Count " . count($this->pages) . " >>\nendobj\n";
        $objId = 3;

        foreach ($this->pages as $p => $pageItems) {
            $pageObjId = $objId;
            $contentObjId = $objId + 1;
            $fontStartObj = $contentObjId + 1;

            $usedFonts = [];
            $pageImages = [];
            $graphics = '';
            $textContent = '';

            foreach ($pageItems as $item) {
                if ($item['type'] == 'image') {
                    $ix = $this->mmToPt($item['x']);
                    $iy = $this->mmToPt($item['y']);
                    $iw = $this->mmToPt($item['w']);
                    $ih = $this->mmToPt($item['h']);
                    $graphics .= "q\n{$iw} 0 0 {$ih} {$ix} " . ($pageH - $iy - $ih) . " cm\n/{$item['id']} Do\nQ\n";
                    $pageImages[] = $item['id'];
                } elseif ($item['type'] == 'cell') {
                    $fs = $item['size'];
                    $fkey = $item['font'] . $item['style'];
                    if (!isset($usedFonts[$fkey])) {
                        $usedFonts[$fkey] = count($usedFonts) + 1;
                    }
                    $fn = $usedFonts[$fkey];
                    $txt = $this->escapeText($item['txt']);
                    $cellX = $this->mmToPt($item['x']);
                    $cellY = $this->mmToPt($item['y']);
                    $cellW = $this->mmToPt($item['w']);
                    $cellH = $this->mmToPt($item['h']);
                    $textW = strlen($item['txt']) * $fs * 0.5;
                    $pad = $fs * 0.35;
                    if ($item['align'] == 'C') {
                        $tx = $cellX + ($cellW - $textW) / 2;
                    } elseif ($item['align'] == 'R') {
                        $tx = $cellX + $cellW - $textW - $pad;
                    } else {
                        $tx = $cellX + $pad;
                    }
                    // Vertical center text within cell
                    $ty = $pageH - $cellY - ($cellH / 2) + ($fs * 0.18);
                    $tr = ($item['tr'] ?? 0) / 255;
                    $tg = ($item['tg'] ?? 0) / 255;
                    $tb = ($item['tb'] ?? 0) / 255;
                    $textContent .= "{$tr} {$tg} {$tb} rg\n";
                    $textContent .= "/F{$fn} {$fs} Tf\n";
                    $textContent .= "1 0 0 1 {$tx} {$ty} Tm\n";
                    $textContent .= "({$txt}) Tj\n";

                    if ($item['border']) {
                        $bx = $cellX;
                        $by = $pageH - $cellY - $cellH;
                        $graphics .= "{$bx} {$by} {$cellW} {$cellH} re S\n";
                    }
                } elseif ($item['type'] == 'line') {
                    $x1 = $this->mmToPt($item['x1']);
                    $y1 = $this->mmToPt($item['y1']);
                    $x2 = $this->mmToPt($item['x2']);
                    $y2 = $this->mmToPt($item['y2']);
                    $graphics .= "{$x1} " . ($pageH - $y1) . " m\n";
                    $graphics .= "{$x2} " . ($pageH - $y2) . " l\n";
                    $graphics .= "S\n";
                } elseif ($item['type'] == 'rect') {
                    $rx = $this->mmToPt($item['x']);
                    $ry = $this->mmToPt($item['y']);
                    $rw = $this->mmToPt($item['w']);
                    $rh = $this->mmToPt($item['h']);
                    if ($item['fill']) {
                        $fr = ($item['fr'] ?? 0) / 255;
                        $fg = ($item['fg'] ?? 0) / 255;
                        $fb = ($item['fb'] ?? 0) / 255;
                        $graphics .= "{$fr} {$fg} {$fb} rg\n";
                        $graphics .= "{$rx} " . ($pageH - $ry - $rh) . " {$rw} {$rh} re f\n";
                    } else {
                        $graphics .= "{$rx} " . ($pageH - $ry - $rh) . " {$rw} {$rh} re S\n";
                    }
                }
            }

            $content = $graphics . "BT\n" . $textContent . "ET\n";

            $compressed = gzcompress($content);

            $fontMapping = [];
            foreach ($usedFonts as $fkey => $num) {
                preg_match('/^([A-Za-z]+)([BI]*)$/', $fkey, $m);
                $base = $m[1];
                $style = $m[2];
                $bf = $base;
                if ($style == 'B') $bf .= '-Bold';
                elseif ($style == 'I') $bf .= '-Oblique';
                elseif ($style == 'BI' || $style == 'IB') $bf .= '-BoldOblique';
                $fontMapping[$num] = $bf;
            }

            $fontObjIds = [];
            $foid = $fontStartObj;
            for ($i = 1; $i <= count($fontMapping); $i++) {
                $fontObjIds[$i] = $foid++;
            }

            $fontRefs = '';
            foreach ($fontObjIds as $num => $fid) {
                $fontRefs .= "/F{$num} {$fid} 0 R ";
            }

            $xObjRefs = '';
            foreach (array_unique($pageImages) as $imgId) {
                $xObjRefs .= "/{$imgId} {$foid} 0 R ";
                $foid++;
            }
            $procSet = '/PDF /Text' . ($pageImages ? ' /ImageC' : '');

            $offsets[$pageObjId] = strlen($pdf);
            $pdf .= "{$pageObjId} 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {$pageW} {$pageH}] /Contents {$contentObjId} 0 R /Resources << /Font << " . trim($fontRefs) . " >>" . ($xObjRefs ? " /XObject << {$xObjRefs} >>" : '') . " /ProcSet [{$procSet}] >> >>\nendobj\n";

            $offsets[$contentObjId] = strlen($pdf);
            $pdf .= "{$contentObjId} 0 obj\n<< /Length " . strlen($compressed) . " /Filter /FlateDecode >>\nstream\n{$compressed}\nendstream\nendobj\n";

            foreach ($fontObjIds as $num => $fid) {
                $offsets[$fid] = strlen($pdf);
                $pdf .= "{$fid} 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /{$fontMapping[$num]} /Encoding /WinAnsiEncoding >>\nendobj\n";
            }

            foreach (array_unique($pageImages) as $imgId) {
                $imgObjId = $foid;
                $img = $this->images[$imgId];
                $imgData = $img['data'];
                $imgW = $img['w'];
                $imgH = $img['h'];
                if ($img['type'] === 'jpeg') {
                    $filter = '/Filter /DCTDecode';
                } else {
                    $imgData = gzcompress($imgData);
                    $filter = '/Filter /FlateDecode';
                }
                $offsets[$imgObjId] = strlen($pdf);
                $pdf .= "{$imgObjId} 0 obj\n<< /Type /XObject /Subtype /Image /Width {$imgW} /Height {$imgH} /ColorSpace /DeviceRGB /BitsPerComponent 8 {$filter} /Length " . strlen($imgData) . " >>\nstream\n{$imgData}\nendstream\nendobj\n";
                $foid++;
            }

            $objId = $foid;
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n";
        $pdf .= "0 {$objId}\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i < $objId; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }
        $pdf .= "trailer\n<< /Size {$objId} /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF\n";
        return $pdf;
    }

    private function escapeText($text)
    {
        return str_replace(['\\', '(', ')', "\n", "\r", "\t"], ['\\\\', '\\(', '\\)', '\\n', '\\r', '\\t'], $text);
    }
}

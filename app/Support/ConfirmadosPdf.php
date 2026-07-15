<?php

namespace App\Support;

use App\Models\Confirmacion;
use Illuminate\Support\Collection;

class ConfirmadosPdf
{
    private const LOGO_PATH = 'images/invitatorio_horizontal.png';

    private const PAGE_WIDTH = 612;

    private const PAGE_HEIGHT = 792;

    private const LEFT = 48;

    private const RIGHT = 564;

    /**
     * @var array{name: string, data: string, width: int, height: int}|null
     */
    private ?array $logo = null;

    /**
     * @param  Collection<int, Confirmacion>  $confirmaciones
     */
    public static function make(Collection $confirmaciones, string $titulo, string $subtitulo): string
    {
        $builder = new self;

        return $builder->render($confirmaciones, $titulo, $subtitulo);
    }

    /**
     * @param  Collection<int, Confirmacion>  $confirmaciones
     */
    private function render(Collection $confirmaciones, string $titulo, string $subtitulo): string
    {
        $this->logo = $this->loadLogo();
        $pages = [];
        $page = $this->newPage($titulo, $subtitulo, $confirmaciones->count());
        $rowNumber = 1;
        $y = 588;

        if ($confirmaciones->isEmpty()) {
            $page .= $this->text('Aún no hay confirmaciones registradas.', self::LEFT, $y, 12, '0.35 0.32 0.38');
        }

        foreach ($confirmaciones as $confirmacion) {
            if ($y < 92) {
                $pages[] = $this->finishPage($page, count($pages) + 1);
                $page = $this->newPage($titulo, $subtitulo, $confirmaciones->count());
                $y = 588;
            }

            $fill = $rowNumber % 2 === 0 ? '0.99 0.98 0.96' : '1 1 1';
            $page .= $this->rect(self::LEFT, $y - 10, self::RIGHT - self::LEFT, 34, $fill);
            $page .= $this->text((string) $rowNumber, 62, $y + 1, 10, '0.36 0.19 0.53');
            $page .= $this->text($this->limit($confirmacion->nombre, 36), 98, $y + 1, 11, '0.10 0.07 0.12');
            $page .= $this->text($this->limit($confirmacion->invitacion?->nombre_completo ?? 'Sin invitación', 28), 302, $y + 1, 10, '0.31 0.29 0.34');
            $page .= $this->text($confirmacion->created_at?->format('d/m/Y H:i') ?? '-', 472, $y + 1, 10, '0.31 0.29 0.34');

            $rowNumber++;
            $y -= 38;
        }

        $pages[] = $this->finishPage($page, count($pages) + 1);

        return $this->buildPdf($pages);
    }

    private function newPage(string $titulo, string $subtitulo, int $total): string
    {
        $date = now()->format('d/m/Y H:i');
        $content = $this->rect(0, 0, self::PAGE_WIDTH, self::PAGE_HEIGHT, '1 1 1');

        if ($this->logo) {
            $content .= $this->image($this->logo['name'], self::LEFT, 724, 116, 50);
        } else {
            $content .= $this->text('Invitatorio', self::LEFT, 748, 16, '0.92 0.46 0.07');
        }

        $content .= $this->text('Generado: '.$date, 430, 752, 9, '0.38 0.35 0.40');
        $content .= $this->text('Total: '.$total, 430, 733, 12, '0.92 0.35 0.00');
        $content .= $this->line(self::LEFT, 708, self::RIGHT, 708, '0.92 0.46 0.07', 1);
        $content .= $this->text($titulo, self::LEFT, 672, 19, '0.17 0.08 0.25');
        $content .= $this->text($subtitulo, self::LEFT, 650, 10, '0.38 0.35 0.40');
        $content .= $this->line(self::LEFT, 628, self::RIGHT, 628, '0.89 0.87 0.91', 0.6);
        $content .= $this->text('#', 62, 638, 9, '0.17 0.08 0.25');
        $content .= $this->text('Nombre', 98, 638, 9, '0.17 0.08 0.25');
        $content .= $this->text('Invitación', 302, 638, 9, '0.17 0.08 0.25');
        $content .= $this->text('Fecha', 472, 638, 9, '0.17 0.08 0.25');
        $content .= $this->line(self::LEFT, 620, self::RIGHT, 620, '0.89 0.87 0.91', 0.6);

        return $content;
    }

    private function finishPage(string $content, int $pageNumber): string
    {
        return $content.$this->text('Página '.$pageNumber, 510, 42, 9, '0.50 0.47 0.53');
    }

    /**
     * @param  array<int, string>  $pages
     */
    private function buildPdf(array $pages): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
        ];

        $kids = [];
        $objectId = 4;
        $logoObjectId = null;

        if ($this->logo) {
            $logoObjectId = $objectId++;
            $objects[$logoObjectId] = '<< /Type /XObject /Subtype /Image /Width '.$this->logo['width'].' /Height '.$this->logo['height'].' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length '.strlen($this->logo['data'])." >>\nstream\n".$this->logo['data'].'endstream';
        }

        foreach ($pages as $content) {
            $pageId = $objectId++;
            $contentId = $objectId++;
            $kids[] = $pageId.' 0 R';

            $xObject = $logoObjectId ? ' /XObject << /'.$this->logo['name'].' '.$logoObjectId.' 0 R >>' : '';
            $objects[$pageId] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 '.self::PAGE_WIDTH.' '.self::PAGE_HEIGHT.'] /Resources << /Font << /F1 3 0 R >>'.$xObject.' >> /Contents '.$contentId.' 0 R >>';
            $objects[$contentId] = '<< /Length '.strlen($content)." >>\nstream\n".$content.'endstream';
        }

        $objects[2] = '<< /Type /Pages /Kids ['.implode(' ', $kids).'] /Count '.count($pages).' >>';

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $id => $object) {
            $offsets[$id] = strlen($pdf);
            $pdf .= $id." 0 obj\n".$object."\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $pdf .= "startxref\n".$xref."\n%%EOF";

        return $pdf;
    }

    private function rect(float $x, float $y, float $w, float $h, string $rgb): string
    {
        return $rgb." rg\n".$this->num($x).' '.$this->num($y).' '.$this->num($w).' '.$this->num($h)." re f\n";
    }

    private function line(float $x1, float $y1, float $x2, float $y2, string $rgb, float $width): string
    {
        return $rgb." RG\n".$this->num($width).' w '.$this->num($x1).' '.$this->num($y1).' m '.$this->num($x2).' '.$this->num($y2)." l S\n";
    }

    private function image(string $name, float $x, float $y, float $w, float $h): string
    {
        return 'q '.$this->num($w).' 0 0 '.$this->num($h).' '.$this->num($x).' '.$this->num($y).' cm /'.$name." Do Q\n";
    }

    private function text(string $text, float $x, float $y, int $size, string $rgb): string
    {
        return $rgb." rg\nBT /F1 ".$size.' Tf '.$this->num($x).' '.$this->num($y).' Td ('.$this->escape($text).") Tj ET\n";
    }

    private function escape(string $text): string
    {
        $encoded = @iconv('UTF-8', 'Windows-1252//TRANSLIT', $text) ?: $text;

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $encoded);
    }

    private function limit(string $text, int $length): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length - 3).'...';
    }

    private function num(float $number): string
    {
        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }

    /**
     * @return array{name: string, data: string, width: int, height: int}|null
     */
    private function loadLogo(): ?array
    {
        $path = public_path(self::LOGO_PATH);

        if (! function_exists('imagecreatefrompng') || ! is_file($path)) {
            return null;
        }

        $source = imagecreatefrompng($path);

        if (! $source) {
            return null;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $canvas = imagecreatetruecolor($width, $height);

        if (! $canvas) {
            return null;
        }

        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $white);
        imagecopy($canvas, $source, 0, 0, 0, 0, $width, $height);

        ob_start();
        imagejpeg($canvas, null, 82);
        $data = (string) ob_get_clean();

        return [
            'name' => 'Logo',
            'data' => $data,
            'width' => $width,
            'height' => $height,
        ];
    }
}

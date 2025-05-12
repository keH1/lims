<?php

class Converter extends Model
{
    public function convertAndDownload($docxPath, $outputFileName = null)
    {
        ob_start();

        try {
            $pdfFile = $this->convertDocxToPdf($docxPath, $outputFileName);
            
            if ($pdfFile && file_exists($pdfFile)) {
                while (ob_get_level()) {
                    ob_end_clean();
                }
                
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $outputFileName . '.pdf"');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($pdfFile));
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');
                
                if (function_exists('apache_setenv')) {
                    apache_setenv('no-gzip', '1');
                }
                ini_set('zlib.output_compression', '0');
                
                readfile($pdfFile);
                
                @unlink($pdfFile);
                @unlink($docxPath);
                
                exit;
            } else {
                $this->downloadFile($docxPath, $outputFileName . '.docx');
            }
        } catch (Exception $e) {
            $this->downloadFile($docxPath, $outputFileName . '.docx');
        }
    }

    public function convertDocxToPdf($docxPath = null, $outputFileName = null, $tempDir = '')
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
        
        $wkhtmltopdfPath = '/usr/local/bin/wkhtmltopdf';

        try {
            if (!file_exists($docxPath)) {
                throw new Exception("Нет исходного файла");
            }
            
            if (!$outputFileName) {
                $outputFileName = pathinfo($docxPath, PATHINFO_FILENAME);
            }

            // $tempDir = $_SERVER['DOCUMENT_ROOT'] . '/ulab/temp';

            if (!is_dir($tempDir)) {
                if (!mkdir($tempDir, 0755, true)) {
                    throw new Exception("Не удалось создать временную директорию");
                }
            }
            
            if (!file_exists($wkhtmltopdfPath)) {
                throw new Exception("wkhtmltopdf нет");
            }
            
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($docxPath);
            
            $htmlFile = $tempDir . '/temp_' . time() . '.html';
            $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
            $htmlWriter->save($htmlFile);
            
            
            $html = file_get_contents($htmlFile);
            $html = '
            <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <title>' . htmlspecialchars($outputFileName) . '</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { border-collapse: collapse; width: 100%; border: none !important; }
                        table * { border: none !important; }
                        td, th { border: none !important; padding: 8px; }
                        h1, h2, h3 { color: #333; }
                    </style>
                </head>
                <body>' . $html . '
                </body>
            </html>';
            
            $html = preg_replace('/<table([^>]*)border=["\']?[0-9]+["\']?/i', '<table$1border="0"', $html);
            $html = preg_replace('/<td([^>]*)border=["\']?[0-9]+["\']?/i', '<td$1border="0"', $html);
            $html = preg_replace('/<th([^>]*)border=["\']?[0-9]+["\']?/i', '<th$1border="0"', $html);
            $html = preg_replace('/style=(["\'])[^"\']*?border[^"\']*?\1/i', 'style="border:none !important;"', $html);
            
            file_put_contents($htmlFile, $html);
            
            $pdfFile = $tempDir . $outputFileName . '.pdf';
            $command = escapeshellcmd($wkhtmltopdfPath) . ' ' . 
                       escapeshellarg($htmlFile) . ' ' . 
                       escapeshellarg($pdfFile) . ' 2>&1';
            
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                throw new Exception("Ошибка при запуске wkhtmltopdf");
            }
           
            @unlink($htmlFile);
            
            return $pdfFile;
            
        } catch (Exception $e) {
            echo 'Ошибка конвертации ' . $e->getMessage();
            return false;
        }
    }
    
    public function downloadFile($filePath, $fileName)
    {
        try {
            if (!file_exists($filePath)) {
                throw new Exception("Нет файла - " . $filePath);
            }
            
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $contentType = 'application/octet-stream';
            
            if (strtolower($fileExt) === 'pdf') {
                $contentType = 'application/pdf';
            } elseif (strtolower($fileExt) === 'docx') {
                $contentType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            }
            
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $contentType);
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            if (function_exists('apache_setenv')) {
                apache_setenv('no-gzip', '1');
            }
            ini_set('zlib.output_compression', '0');
            
            readfile($filePath);
            exit;
        } catch (Exception $e) {
            header('Content-Type: text/plain');
            echo "Ошибка при скачвиании файла - " . $e->getMessage();
            exit;
        }
    }
}
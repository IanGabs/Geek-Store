<?php
class CsvConverter
{
    public function convertToCsv(array $data): string
    {
        if(empty($data)) {
            return '';
        }

        $stream = fopen('php://memory', 'w');
        fputcsv($stream, array_keys($data[0]));

        foreach($data as $row) {
            fputcsv($stream, $row);
        }
        
        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);

        return $csv;
    }
}
?>
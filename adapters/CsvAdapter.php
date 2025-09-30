<?php
class CsvAdapter implements DataExporter
{
    private $csvConverter;

    public function __construct(CsvConverter $csvConverter)
    {
        $this->csvConverter = $csvConverter;
    }

    public function export(array $data): string
    {
        return $this->csvConverter->convertToCsv($data);
    }
}
?>
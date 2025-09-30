<?php
class JsonExporter implements DataExporter 
{
    public function export(array $data): string 
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
?>
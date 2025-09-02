<?php
interface DataExporter 
{
    public function export(array $data): string;
}
?>
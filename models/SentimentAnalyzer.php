<?php
class SentimentAnalyzer {
    private $positivas = [
        'excelente', 'bom', 'boa', 'ótimo', 'ótima', 'perfeito', 'perfeita', 
        'amei', 'adoro', 'adorei', 'lindo', 'linda', 'maravilhoso', 'rápido', 
        'eficaz', 'top', 'recomendo', 'feliz', 'gostei', 'incrivel', 'incrível',
        'macio', 'macia', 'qualidade', 'legal', 'bonito', 'parabéns', 'show'
    ];

    private $negativas = [
        'ruim', 'péssimo', 'péssima', 'horrível', 'feio', 'feia', 'demorado', 
        'lento', 'quebrado', 'defeito', 'odiei', 'detestei', 'caro', 'triste', 
        'errado', 'pior', 'nunca', 'falha', 'problema', 'sujo', 'rasgado',
        'bosta', 'lixo', 'inútil', 'fraco', 'frágil'
    ];

    public function analisar($texto) {
        $texto = strtolower($texto);
        $texto = str_replace([',', '.', '!', '?'], '', $texto);
        $palavras = explode(' ', $texto);

        $score = 0;

        foreach ($palavras as $palavra) {
            if (in_array($palavra, $this->positivas)) {
                $score++;
            } elseif (in_array($palavra, $this->negativas)) {
                $score--;
            }
        }

        if ($score > 0) {
            return 'positivo';
        } elseif ($score < 0) {
            return 'negativo';
        } else {
            return 'neutro';
        }
    }
}
?>
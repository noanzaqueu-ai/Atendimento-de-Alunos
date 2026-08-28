<?php

class Solution {
    private $MOD = 1000000007;
    
    /**
     * @param Integer $n
     * @return Integer
     */
    function checkRecord($n) {
        $MOD = $this->MOD;

        // Se n = 1, temos 3 possibilidades: 'P', 'L', 'A'
        if ($n == 1) {
            return 3;
        }

        // Se n = 2, temos 8 possibilidades (todas exceto 'AA')
        if ($n == 2) {
            return 8;
        }

        // dp[i][j][k] representa o número de sequências de tamanho i
        // onde j = número de faltas (A) (0 ou 1)
        // e k = número de atrasos consecutivos (L) no final (0, 1, ou 2)

        // Inicializamos para n = 1
        $dp = array_fill(0, $n + 1, array_fill(0, 2, array_fill(0, 3, 0)));

        // Base cases para n = 1
        $dp[1][0][0] = 1; // "P"
        $dp[1][0][1] = 1; // "L"
        $dp[1][1][0] = 1; // "A"

        // Preenchemos a tabela DP
        for ($i = 2; $i <= $n; $i++) {
            // Sem faltas (j = 0)
            $dp[$i][0][0] = ($dp[$i-1][0][0] + $dp[$i-1][0][1] + $dp[$i-1][0][2]) % $MOD;
            $dp[$i][0][1] = $dp[$i-1][0][0];
            $dp[$i][0][2] = $dp[$i-1][0][1];

            // Com 1 falta (j = 1)
            $dp[$i][1][0] = ($dp[$i-1][1][0] + $dp[$i-1][1][1] + $dp[$i-1][1][2] +
                             $dp[$i-1][0][0] + $dp[$i-1][0][1] + $dp[$i-1][0][2]) % $MOD;
            $dp[$i][1][1] = $dp[$i-1][1][0];
            $dp[$i][1][2] = $dp[$i-1][1][1];
        }

        // Somamos todas as possibilidades válidas
        $result = 0;
        for ($j = 0; $j <= 1; $j++) {
            for ($k = 0; $k <= 2; $k++) {
                $result = ($result + $dp[$n][$j][$k]) % $MOD;
            }
        }

        return $result;
    }
    
    /**
     * NOVA FUNCIONALIDADE: Gerar exemplos de registros válidos
     * @param Integer $n
     * @param Integer $count Número de exemplos a gerar
     * @return Array Lista de registros válidos
     */
    function generateValidRecords($n, $count = 5) {
        $validRecords = [];
        $attempts = 0;
        $maxAttempts = $count * 50; // Limite para evitar loop infinito
        
        while (count($validRecords) < $count && $attempts < $maxAttempts) {
            $record = $this->generateRandomRecord($n);
            if ($this->isValidRecord($record)) {
                if (!in_array($record, $validRecords)) {
                    $validRecords[] = $record;
                }
            }
            $attempts++;
        }
        
        return $validRecords;
    }
    
    /**
     * NOVA FUNCIONALIDADE: Gerar um registro aleatório
     * @param Integer $n
     * @return String
     */
    private function generateRandomRecord($n) {
        $chars = ['P', 'A', 'L'];
        $record = '';
        
        for ($i = 0; $i < $n; $i++) {
            $record .= $chars[array_rand($chars)];
        }
        
        return $record;
    }
    
    /**
     * NOVA FUNCIONALIDADE: Verificar se um registro é válido
     * @param String $record
     * @return Boolean
     */
    function isValidRecord($record) {
        // Contar número de faltas (A)
        $absentCount = substr_count($record, 'A');
        if ($absentCount >= 2) {
            return false;
        }
        
        // Verificar se há 3 ou mais atrasos consecutivos (L)
        if (strpos($record, 'LLL') !== false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * NOVA FUNCIONALIDADE: Análise estatística dos registros válidos
     * @param Integer $n
     * @return Array Estatísticas detalhadas
     */
    function analyzeRecords($n) {
        $stats = [
            'total_valid_records' => $this->checkRecord($n),
            'records_without_absence' => 0,
            'records_with_one_absence' => 0,
            'records_ending_with_P' => 0,
            'records_ending_with_L' => 0,
            'records_ending_with_A' => 0,
            'percentage_without_absence' => 0,
            'percentage_with_one_absence' => 0,
            'examples' => []
        ];
        
        // Calcular distribuição usando DP
        $MOD = $this->MOD;
        $dp = array_fill(0, $n + 1, array_fill(0, 2, array_fill(0, 3, 0)));
        
        if ($n >= 1) {
            $dp[1][0][0] = 1; // P
            $dp[1][0][1] = 1; // L
            $dp[1][1][0] = 1; // A
        }
        
        for ($i = 2; $i <= $n; $i++) {
            // Sem faltas
            $dp[$i][0][0] = ($dp[$i-1][0][0] + $dp[$i-1][0][1] + $dp[$i-1][0][2]) % $MOD;
            $dp[$i][0][1] = $dp[$i-1][0][0];
            $dp[$i][0][2] = $dp[$i-1][0][1];
            
            // Com 1 falta
            $dp[$i][1][0] = ($dp[$i-1][1][0] + $dp[$i-1][1][1] + $dp[$i-1][1][2] +
                             $dp[$i-1][0][0] + $dp[$i-1][0][1] + $dp[$i-1][0][2]) % $MOD;
            $dp[$i][1][1] = $dp[$i-1][1][0];
            $dp[$i][1][2] = $dp[$i-1][1][1];
        }
        
        // Calcular estatísticas
        for ($k = 0; $k <= 2; $k++) {
            $stats['records_without_absence'] = ($stats['records_without_absence'] + $dp[$n][0][$k]) % $MOD;
            $stats['records_with_one_absence'] = ($stats['records_with_one_absence'] + $dp[$n][1][$k]) % $MOD;
        }
        
        // Registros terminando com P
        $stats['records_ending_with_P'] = ($dp[$n][0][0] + $dp[$n][1][0]) % $MOD;
        
        // Registros terminando com L
        $stats['records_ending_with_L'] = ($dp[$n][0][1] + $dp[$n][0][2] + 
                                           $dp[$n][1][1] + $dp[$n][1][2]) % $MOD;
        
        // Registros terminando com A (apenas para j=1)
        $stats['records_ending_with_A'] = $dp[$n][1][0];
        
        // Calcular percentuais
        if ($stats['total_valid_records'] > 0) {
            $stats['percentage_without_absence'] = round(($stats['records_without_absence'] * 100) / $stats['total_valid_records'], 2);
            $stats['percentage_with_one_absence'] = round(($stats['records_with_one_absence'] * 100) / $stats['total_valid_records'], 2);
        }
        
        // Gerar exemplos
        $stats['examples'] = $this->generateValidRecords($n, 3);
        
        return $stats;
    }
    
    /**
     * NOVA FUNCIONALIDADE: Verificar se uma combinação específica é válida
     * @param String $record
     * @return Array Resultado da verificação com detalhes
     */
    function validateRecord($record) {
        $result = [
            'record' => $record,
            'is_valid' => $this->isValidRecord($record),
            'length' => strlen($record),
            'absence_count' => substr_count($record, 'A'),
            'late_count' => substr_count($record, 'L'),
            'present_count' => substr_count($record, 'P'),
            'has_three_consecutive_lates' => strpos($record, 'LLL') !== false,
            'issues' => []
        ];
        
        if ($result['absence_count'] >= 2) {
            $result['issues'][] = "Possui 2 ou mais ausências (A)";
        }
        
        if ($result['has_three_consecutive_lates']) {
            $result['issues'][] = "Possui 3 ou mais atrasos consecutivos (L)";
        }
        
        if (empty($result['issues'])) {
            $result['issues'][] = "Nenhum problema encontrado";
        }
        
        return $result;
    }
}

// Demonstração das novas funcionalidades
echo "=== DEMONSTRAÇÃO DAS NOVAS FUNCIONALIDADES ===\n\n";

$solution = new Solution();

// Teste original
echo "1. FUNCIONALIDADE ORIGINAL:\n";
echo "n = 2: " . $solution->checkRecord(2) . "\n";
echo "n = 1: " . $solution->checkRecord(1) . "\n";
echo "n = 10101: " . $solution->checkRecord(10101) . "\n\n";

// Nova funcionalidade: Gerar exemplos válidos
echo "2. GERAR EXEMPLOS VÁLIDOS (n = 5):\n";
$examples = $solution->generateValidRecords(5, 3);
foreach ($examples as $index => $record) {
    echo "Exemplo " . ($index + 1) . ": " . $record . "\n";
}
echo "\n";

// Nova funcionalidade: Análise estatística
echo "3. ANÁLISE ESTATÍSTICA (n = 10):\n";
$stats = $solution->analyzeRecords(10);
echo "Total de registros válidos: " . $stats['total_valid_records'] . "\n";
echo "Registros sem ausência: " . $stats['records_without_absence'] . " (" . $stats['percentage_without_absence'] . "%)\n";
echo "Registros com 1 ausência: " . $stats['records_with_one_absence'] . " (" . $stats['percentage_with_one_absence'] . "%)\n";
echo "Terminando com P: " . $stats['records_ending_with_P'] . "\n";
echo "Terminando com L: " . $stats['records_ending_with_L'] . "\n";
echo "Terminando com A: " . $stats['records_ending_with_A'] . "\n";
echo "Exemplos: " . implode(", ", $stats['examples']) . "\n\n";

// Nova funcionalidade: Validar registro específico
echo "4. VALIDAR REGISTROS ESPECÍFICOS:\n";
$testRecords = ["PPALLP", "PPALLL", "AA", "PPALL", "PPALLLP"];
foreach ($testRecords as $record) {
    $validation = $solution->validateRecord($record);
    echo "Registro: " . $record . "\n";
    echo "  Válido: " . ($validation['is_valid'] ? "Sim" : "Não") . "\n";
    echo "  Comprimento: " . $validation['length'] . "\n";
    echo "  Ausências (A): " . $validation['absence_count'] . "\n";
    echo "  Atrasos (L): " . $validation['late_count'] . "\n";
    echo "  Presenças (P): " . $validation['present_count'] . "\n";
    echo "  Problemas: " . implode("; ", $validation['issues']) . "\n\n";
}

?>

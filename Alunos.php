<?php

class Solution {
    /**
     * @param Integer $n
     * @return Integer
     */
    function checkRecord($n) {
        $MOD = 1000000007;

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

            // k = 0 (terminando com P)
            $dp[$i][0][0] = ($dp[$i-1][0][0] + $dp[$i-1][0][1] + $dp[$i-1][0][2]) % $MOD;

            // k = 1 (terminando com L, após 0 L's consecutivos)
            $dp[$i][0][1] = $dp[$i-1][0][0];

            // k = 2 (terminando com LL, após 1 L consecutivo)
            $dp[$i][0][2] = $dp[$i-1][0][1];

            // Com 1 falta (j = 1)

            // k = 0 (terminando com P ou A)
            $dp[$i][1][0] = ($dp[$i-1][1][0] + $dp[$i-1][1][1] + $dp[$i-1][1][2] +
                             $dp[$i-1][0][0] + $dp[$i-1][0][1] + $dp[$i-1][0][2]) % $MOD;

            // k = 1 (terminando com L, após 0 L's consecutivos)
            $dp[$i][1][1] = $dp[$i-1][1][0];

            // k = 2 (terminando com LL, após 1 L consecutivo)
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
}

// Teste dos exemplos
$solution = new Solution();

// Exemplo 1
echo "n = 2: " . $solution->checkRecord(2) . "\n"; // Deve retornar 8

// Exemplo 2
echo "n = 1: " . $solution->checkRecord(1) . "\n"; // Deve retornar 3

// Exemplo 3
echo "n = 10101: " . $solution->checkRecord(10101) . "\n"; // Deve retornar 183236316

?>

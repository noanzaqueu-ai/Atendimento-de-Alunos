# Atendimento-de-Alunos

## 📝 Descrição do Problema

Um registro de presença de um estudante pode ser representado como uma string onde cada caractere indica se o estudante estava **ausente**, **atrasado** ou **presente** naquele dia. O registro contém apenas os seguintes três caracteres:

- `'A'`: Ausente (Absent)
- `'L'`: Atrasado (Late)
- `'P'`: Presente (Present)

Um estudante é elegível para um prêmio de presença se atender a **ambos** os critérios abaixo:

1. O estudante esteve ausente (`'A'`) por **estritamente menos de 2 dias** no total.
2. O estudante **nunca** esteve atrasado (`'L'`) por **3 ou mais dias consecutivos**.

Dado um inteiro `n`, retorne o **número de registros de presença possíveis** de comprimento `n` que tornam um estudante elegível para o prêmio de presença. Como a resposta pode ser muito grande, retorne o resultado **módulo 10⁹ + 7**.

## 🎯 Exemplos

### Exemplo 1:

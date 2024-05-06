<?php
session_start();

function iniciarOuResumirSessao() {
    if (!isset($_SESSION['historico'])) {
        $_SESSION['historico'] = array();
    }
}

function calcular($num1, $num2, $operacao) {
    iniciarOuResumirSessao();
    $historico = $_SESSION['historico'];
    $resultado = null;
    $itemHistorico = null;

    switch ($operacao) {
        case '+':
            $resultado = $num1 + $num2;
            break;
        case '-':
            $resultado = $num1 - $num2;
            break;
        case '*':
            $resultado = $num1 * $num2;
            break;
        case '/':
            if ($num2 != 0) {
                $resultado = $num1 / $num2;
            } else {
                $resultado = "Erro: divisão por zero!";
            }
            break;
        default:
            $resultado = "Operação inválida!";
            break;
    }

    $itemHistorico = "$num1 $operacao $num2 = $resultado";
    $historico[] = $itemHistorico;
    $_SESSION['historico'] = $historico;
    return $resultado;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['num']) && isset($_POST['operacao'])) {
        $num = $_POST['num'];
        $operacao = $_POST['operacao'];

        if (!isset($_SESSION['num1'])) {
            $_SESSION['num1'] = $num;
            $_SESSION['operacao'] = $operacao;
        } else {
            $num1 = $_SESSION['num1'];
            $num2 = $num;
            $operacao = $_SESSION['operacao'];
            calcular($num1, $num2, $operacao);
            unset($_SESSION['num1']);
        }
    }

    if (isset($_POST['limpar_historico'])) {
        $_SESSION['historico'] = array();
    }

    // Redireciona de volta para o arquivo PHP após o processamento do formulário
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora PHP</title>
    <style>
        .calculadora {
            width: 300px;
            margin: 20px auto;
            background-color: #f0f0f0;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .exibicao input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
        }

        .botoes {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
        }

        .botoes button {
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            background-color: #ddd;
            cursor: pointer;
        }

        .botoes button:hover {
            background-color: #ccc;
        }

        .historico {
            margin-top: 20px;
        }

        .historico h2 {
            margin-bottom: 10px;
        }

        .historico ul {
            list-style: none;
            padding: 0;
        }

        .historico li {
            margin-bottom: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="calculadora">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="exibicao">
                <input type="hidden" name="num" id="num" value="">
                <input type="text" name="expressao" id="expressao" placeholder="Digite o número" required readonly>
            </div>
            <div class="botoes">
                <?php for ($i = 1; $i <= 9; $i++) : ?>
                    <button type="button" onclick="selecionarNumero(<?php echo $i; ?>)"><?php echo $i; ?></button>
                <?php endfor; ?>
                <button type="button" onclick="selecionarNumero(0)">0</button>
                <button type="submit" name="operacao" value="+">+</button>
                <button type="submit" name="operacao" value="-">-</button>
                <button type="submit" name="operacao" value="*">*</button>
                <button type="submit" name="operacao" value="/">/</button>
                <button type="submit" name="operacao" value="=">=</button>
            </div>
        </form>
    </div>
    <div class="historico">
        <h2>Histórico de Operações</h2>
        <ul>
            <?php foreach ($_SESSION['historico'] as $operacao) : ?>
                <li><?php echo $operacao; ?></li>
            <?php endforeach; ?>
        </ul>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <button type="submit" name="limpar_historico">Limpar Histórico</button>
        </form>
    </div>

    <script>
        function selecionarNumero(numero) {
            document.getElementById('num').value += numero;
            document.getElementById('expressao').value = document.getElementById('num').value;
        }
    </script>
</body>
</html>

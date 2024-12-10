<?php
require 'db.php';
require 'auth.php';

// Verifica se o botão foi pressionado para exportar
if (isset($_POST['exportar_csv'])) {
    // Conectar ao banco de dados
    $db = conectar_db();
    $produtos = $db->query("SELECT * FROM produtos")->fetchAll(PDO::FETCH_ASSOC);

    // Nome do arquivo CSV
    $filename = "estoque_produtos.csv";

    // Abrir o arquivo para escrita (o 'php://output' envia diretamente para o navegador)
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Abrir o "arquivo" em modo de escrita
    $output = fopen('php://output', 'w');

    // Escrever o cabeçalho CSV (títulos das colunas)
    fputcsv($output, ['ID', 'Nome', 'Quantidade', 'Preço']);

    // Escrever os dados dos produtos
    foreach ($produtos as $produto) {
        fputcsv($output, [$produto['id'], $produto['nome'], $produto['quantidade'], $produto['preco']]);
    }

    // Fechar o arquivo
    fclose($output);
    exit;
}

// Carregar os produtos do banco de dados para exibição na tabela
$db = conectar_db();
$produtos = $db->query("SELECT * FROM produtos")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/header.php'; ?>
<body>
<div class="container">
    <h1>Estoque de Itens no Laboratório</h1>
    <nav>
            <button> <a href="adicionar.php">Adicionar Item</a></button>
            <br>
            <br>
            <button><a href="logout.php">Logout</a></button>
    </nav>

    <h2>Itens no Inventário</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['id']) ?></td>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td><?= htmlspecialchars($produto['quantidade']) ?></td>
                    <td><?= htmlspecialchars($produto['preco']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $produto['id'] ?>">Editar</a> |
                        <a href="excluir.php?id=<?= $produto['id'] ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Botão de exportação para CSV -->
    <form method="post" action="">
        <button type="submit" name="exportar_csv">Exportar estoque para CSV</button>
    </form>

    <h2>Gráfico de Quantidade de Produtos</h2>
    <canvas id="graficoProdutos" width="400" height="200"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('graficoProdutos').getContext('2d');
        const produtos = <?= json_encode($produtos) ?>;
        const nomes = produtos.map(p => p.nome);
        const quantidades = produtos.map(p => p.quantidade);

        new Chart(ctx, {
            type: 'bar',  // Tipo de gráfico
            data: {
                labels: nomes,  // Nomes dos produtos como rótulos
                datasets: [{
                    label: 'Quantidade de Produtos',  // Título do gráfico
                    data: quantidades,  // Quantidade de cada produto
                    backgroundColor: '#a82223',  // Cor das barras
                }]
            }
        });
    </script>
    <br>
          <h2>Clique no Gengar para retornar ao menu</h2>
          <a href="index.html" alt="torre">
            <img src="gengar.gif" height="175px" width="175px"/>
          </a>
          <br>
          <br>
          <a href="index.html" alt="torre">
            <img src="acessibilidade.png" height="55px" width="55px"/>
          </a>
</div>
</body>
</html>

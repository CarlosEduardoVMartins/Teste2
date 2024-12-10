<?php
require 'db.php';
require 'auth.php';

$db = conectar_db();
$produtos = $db->query("SELECT * FROM produtos")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/header.php'; ?>
<body>
<div class="container">
    <h1>Estoque de Intens no laboratório</h1>
    <nav>
        <ul>
            <li><a href="adicionar.php">Adicionar Item</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
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
    </div>
</body>
</html>
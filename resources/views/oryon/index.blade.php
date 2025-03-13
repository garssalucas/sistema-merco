<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos Oryon</title>
</head>
<body>
    <h1>Lista de Produtos Oryon</h1>

    <!-- Mensagens de sucesso ou erro -->
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <!-- Tabela para exibir os produtos -->
    <table border="1">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Fornecedor</th>
                <th>Peso</th>
                <th>Preço de Compra</th>
                <th>Estoque</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $produto)
                <tr>
                    <td>{{ $produto->codigo }}</td>
                    <td>{{ $produto->descricao }}</td>
                    <td>{{ number_format($produto->preco, 2, ',', '.') }}</td>
                    <td>{{ $produto->categoria }}</td>
                    <td>{{ $produto->fornecedor }}</td>
                    <td>{{ number_format($produto->peso, 2, ',', '.') }}</td>
                    <td>{{ number_format($produto->preco_compra, 2, ',', '.') }}</td>
                    <td>{{ $produto->estoque }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('oryon.importar') }}">Importar Produtos</a>
</body>
</html>
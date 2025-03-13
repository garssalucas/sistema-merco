<?php

namespace App\Http\Controllers;

use App\Models\ProdutoOryon;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions; // Importa a classe FtpConnectionOptions

class ProdutoOryonController extends Controller
{
    public function index()
    {
        // Recupera todos os produtos da tabela 'produtos_oryon'
        $produtos = ProdutoOryon::all();
        return view('oryon.index', compact('produtos'));  // Atualizado para oryon
    }

    public function importarProdutos()
    {
        // Cria as opções de conexão FTP com FtpConnectionOptions
        $ftpOptions = FtpConnectionOptions::fromArray([
            'host' => env('FTP_HOST'),
            'username' => env('FTP_USER'),
            'password' => env('FTP_PASS'),
            'port' => (int) env('FTP_PORT'),  // Garantindo que o valor de port seja um inteiro
            'root' => env('FTP_ROOT'),
            'passive' => true,
            'ssl' => false,
        ]);

        // Cria o adaptador FTP com as opções corretas
        $adapter = new FtpAdapter($ftpOptions);  // Passando o objeto FtpConnectionOptions
        $filesystem = new Filesystem($adapter);

        // Caminho para o arquivo no servidor FTP
        $ftpFile = env('FTP_FILE');

        // Obtém o conteúdo do arquivo
        if ($filesystem->has($ftpFile)) {
            $content = $filesystem->read($ftpFile);
            // Garante que o conteúdo esteja em UTF-8 para evitar problemas com acentos
            $content = mb_convert_encoding($content, 'UTF-8', 'auto');
            
            $lines = explode("\n", $content);
            array_shift($lines);
            // Converte o conteúdo do arquivo para um array
            foreach ($lines as $line) {
                $columns = str_getcsv($line, ';'); // Considerando que o separador é ponto e vírgula
                
                if (count($columns) == 9) {  // Verifica se o arquivo tem o número correto de colunas
                    // Faz o mapeamento dos dados
                    $produtoData = [
                        'codigo' => $columns[0],
                        'descricao' => $columns[1],
                        'preco' => str_replace(',', '.', $columns[2]),  // Substitui vírgula por ponto
                        'categoria' => $columns[3],
                        'fornecedor' => $columns[4],
                        'peso' => empty($columns[5]) ? null : str_replace(',', '.', $columns[5]),  // Verifica se 'peso' está vazio e usa NULL
                        'preco_compra' => str_replace(',', '.', $columns[6]),  // Substitui vírgula por ponto
                        'estoque' => str_replace(',', '.', $columns[7]), 
                    ];

                    // Verifica se o produto já existe no banco de dados
                    $produto = ProdutoOryon::updateOrCreate(
                        ['codigo' => $produtoData['codigo']],  // Verifica pelo código
                        $produtoData  // Atualiza ou cria com os dados do arquivo
                    );
                }
            }

            // Após importar, pode redirecionar para a página de produtos com sucesso
            return redirect()->route('oryon.index')->with('success', 'Produtos importados com sucesso!');
        } else {
            return redirect()->route('oryon.index')->with('error', 'Erro ao acessar o arquivo FTP.');
        }
    }
}
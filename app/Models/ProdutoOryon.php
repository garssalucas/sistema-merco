<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoOryon extends Model {
    use HasFactory;

    protected $table = 'produtos_oryon';

    protected $fillable = [
        'codigo',
        'descricao',
        'preco',
        'categoria',
        'fornecedor',
        'peso',
        'preco_compra',
        'estoque'
    ];
}

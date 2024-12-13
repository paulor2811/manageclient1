<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Especifica o nome da tabela 
    protected $table = 'users_tbl'; 
    
    // Especifica que a chave primária é `id` 
    protected $primaryKey = 'id'; 

    // Especifica os campos que podem ser preenchidos em massa 
    protected $fillable = [ 
        'nome', 
        'cep', 
        'endereco', 
        'bairro', 
        'cidade', 
        'uf', 
        'telefone', 
        'email' 
    ];
}

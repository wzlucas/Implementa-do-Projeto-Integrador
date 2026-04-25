<?php

require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/Config.php';

use app\core\Router;

$router = new Router();

// Rotas públicas
$router->get('/', 'JogadorController@listarTodos');
$router->get('/jogadores', 'JogadorController@listarTodos');
$router->get('/jogadores/jogador', 'JogadorController@verJogador');
$router->get('/login', 'AutenticacaoController@login');
$router->post('/logar', 'AutenticacaoController@logar');

// Rotas protegidas (Jogadores)
$router->get('/jogadores/cadastrar', 'JogadorController@criar');
$router->post('/jogadores/salvar', 'JogadorController@salvar');

// Rotas de Usuários (CRUD completo)
$router->get('/usuarios', 'UsuarioController@index');
$router->get('/usuarios/cadastrar', 'UsuarioController@cadastrar');
$router->post('/usuarios/salvar', 'UsuarioController@salvar');
$router->get('/usuarios/editar', 'UsuarioController@editar');
$router->post('/usuarios/atualizar', 'UsuarioController@atualizar');
$router->get('/usuarios/excluir', 'UsuarioController@excluir');

$router->run();
<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\Validador;
use app\models\Usuario;
use app\services\UsuarioService;

class UsuarioController extends Controller
{
    private UsuarioService $service;

    public function __construct(){
        $this->service = new UsuarioService();
    }

    public function index(){
        $this->adminRequired(); // só admin vê lista de usuários
        $data['usuarios'] = $this->service->getUsuarios();
        $this->view('usuarios/usuario_list', $data);
    }

    public function cadastrar(){
        $this->adminRequired();
        $this->view('usuarios/usuario_create', ['usuario' => [], 'erros' => []]);
    }

    public function salvar(){
        $this->adminRequired();

        $validador = new Validador();

        $nomeUsuario = trim(filter_input(INPUT_POST, 'nomeUsuario', FILTER_SANITIZE_SPECIAL_CHARS));
        $email       = trim($_POST['email'] ?? '');
        $senha       = $_POST['senha'] ?? '';
        $perfil      = $_POST['perfil'] ?? 'user';

        $validador->obrigatorio('nomeUsuario', $nomeUsuario);
        $validador->obrigatorio('email', $email);
        $validador->obrigatorio('senha', $senha);

        if ($validador->temErros()) {
            $data['usuario'] = $_POST;
            $data['erros']   = $validador->getErros();
            $this->view('usuarios/usuario_create', $data);
            return;
        }

        $usuario = new Usuario(0, $nomeUsuario, $email, $senha, $perfil);

        if ($this->service->saveUsuario($usuario)) {
            $this->redirect(URL_BASE . '/usuarios');
        } else {
            $data['usuario'] = $_POST;
            $data['erros']['email'] = "Este e-mail já está cadastrado!";
            $this->view('usuarios/usuario_create', $data);
        }
    }

    public function editar(){
        $this->adminRequired();
        $id = (int)($_GET['id'] ?? 0);
        $data['usuario'] = $this->service->getUsuarioById($id);
        $data['erros'] = [];
        $this->view('usuarios/usuario_edit', $data);
    }

    public function atualizar(){
        $this->adminRequired();

        $usuario = new Usuario(
            (int)$_POST['id'],
            trim($_POST['nomeUsuario']),
            trim($_POST['email']),
            $_POST['senha'] ?? '',
            $_POST['perfil']
        );

        $this->service->updateUsuario($usuario);
        $this->redirect(URL_BASE . '/usuarios');
    }

    public function excluir(){
        $this->adminRequired();
        $id = (int)($_GET['id'] ?? 0);
        $this->service->deleteUsuario($id);
        $this->redirect(URL_BASE . '/usuarios');
    }
}
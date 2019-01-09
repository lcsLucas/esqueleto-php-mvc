<?php

namespace App\controllers;

use ProjetoMvc\render\Action;
use App\model\Usuario;
use App\model\Data_Validator;

if (! defined('ABSPATH')){
    header("Location: /");
    exit();
}

class UsuarioController extends Action
{

    public function __construct()
    {
        parent::__construct();
        /**
         * caminho com o arquivo do layout padrão que todas as paginas dessa controller poderá usar
         */
        $this->layoutPadrao = PATH_VIEWS."shared/layoutPadrao";
    }

    /**
     * chama a view de alterar perfil, passando o titulo da página
     * @return void
     * Method GET
     */
    public function pageAlterarPerfil()
    {
        $usu = new Usuario();
        $usu->setId($_SESSION["_idusuario"]);
        $usu->carregarInformacoes();

        $this->dados->informacoes = $usu;
        $this->dados->input_drop = true;
        $this->dados->validation = true;
        $this->dados->title = "Alterar Perfil";
        $this->render('alterar-perfil.php');
    }

    public function requestAlterarPerfil() {
        $validate = new Data_Validator();
        $usuario = new Usuario();

        $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
        $apelido = trim(filter_input(INPUT_POST, 'apelido', FILTER_SANITIZE_SPECIAL_CHARS));
        $token = trim(filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS));
        $file = !empty($_FILES["avatar"]) ? $_FILES["avatar"] : null;

        $validate->define_pattern('erro_');
        $validate
            ->set("nome", $nome)->is_required()
            ->set("apelido", $apelido)->is_required()
            ->set("token", $token)->is_required();

        if ($validate->validate()) {

            if (password_verify(TOKEN_SESSAO, $token)) {

                if (!empty($file)) {

                    if (!$file["error"]) {

                        if ($file["error"] === 1 || $file["error"] === 2)
                            $this->setRetorno("O arquivo \"". $file["name"] ."\" excede o tamanho máximo permitido de 1,5MB.", true, false);
                        elseif($file["error"] === 3)
                            $this->setRetorno("Não foi possível fazer o upload completo do arquivo, tente novamente", true, false);
                        elseif($file["error"] === 4)
                            $this->setRetorno("Não foi enviado nenhum arquivo", true, false);
                        elseif($file["error"] === 6)
                            $this->setRetorno("Não foi possível fazer o upload do arquivo (pasta temporária ausente)", true, false);
                        else
                            $this->setRetorno("Erro inesperável no upload do arquivo, tente novamente", true, false);

                        $erro_img = true;
                    } else if($file["size"] > 1572864){
                        $this->setRetorno("O arquivo \"". $file["name"] ."\" excede o tamanho máximo permitido de 1,5MB.", true, false);
                        $erro_img = true;
                    }
                }

                if (!isset($erro_img)) {

                    $usuario->setNome($nome);
                    $usuario->setApelido($apelido);
                    $usuario->setFileAvatar($file);
                    $usuario->setId($_SESSION["_idusuario"]);

                    if ($usuario->alterarPerfil())
                        $this->setRetorno("Perfil alterado com sucesso", true, false);
                    else if($usuario->getRetorno()["exibir"])
                        $this->setRetorno($usuario->getRetorno()["mensagem"], $usuario->getRetorno()["exibir"], $usuario->getRetorno()["status"]);
                    else
                        $this->setRetorno("Não foi possível alterar seu perfil, tente novamente", true, false);

                }

            } else {
                $this->setRetorno("Token de autenticação inválido", true, false);
            }

        } else {
            $array_erros = $validate->get_errors();
            $array_erro = array_shift($array_erros);
            $erro = array_shift($array_erro);
            $this->setRetorno($erro, true, false);
        }

        $this->dados->retorno = $this->getRetorno();
        $this->pageAlterarPerfil();

        /*header("Location: " . $_SERVER["REQUEST_URI"]);
        exit();*/

    }

}
<?php

$retorno = null;

include_once ABSPATH . "app/funcoesGlobais/paginacao.php";

if (!empty($this->dados->retorno))
    $retorno = $this->dados->retorno;

$lista_registros = $this->dados->registros;

$paginacao = $this->dados->paginacao;

?>

    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= URL ?>">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Usuários</li>
        <li class="breadcrumb-item active">Tipos de usuários</li>
    </ol>

    <div class="animated fadeIn">

        <div id="conteudo" class="container">

            <div id="container-errors">

                <?php

                if (!empty($retorno)) {

                    if (empty($retorno["status"])) {
                        ?>

                        <div class="alert alert-block alert-danger text-center">
                            <a href="javascript:void(0)" class="alert-link position-relative">
                                <i class="fas fa-thumbs-up fa-rotate-180" style="position: absolute;left: -25px;top: 5px;"></i>
                            </a>
                            <?= $retorno["mensagem"] ?>
                        </div>

                        <?php
                    } else {
                        ?>

                        <div class="alert alert-block alert-success text-center">
                            <a href="javascript:void(0)" class="alert-link position-relative"><i class="fas fa-thumbs-up" style="position: absolute;left: -25px;top: 3px;"></i></a> <?= $retorno["mensagem"] ?>
                        </div>

                        <?php
                    }

                }

                ?>

            </div>

            <div class="card border-0">

                <div class="card-header <?= !empty($this->dados->editar) ? "bg-danger" : "bg-primary" ?> py-3">
                    <h5 class="text-uppercase m-0 text-center text-md-left">
                        <?= !empty($this->dados->editar) ? "Editar Tipo de Usuário" : "Gerenciar Tipos de Usuários" ?>
                    </h5>
                </div>

                <div class="card-body border border-top-0 <?= !empty($this->dados->editar) ? "border-danger" : "border-primary" ?>">

                    <form action="<?= $_SERVER["REQUEST_URI"] ?>" method="post" class="form-validate" id="formTipoUsuario">

                        <div class="form-group form-group-lg">
                            <label for="nome" class="font-weight-bold">Tipo de usuários:</label>
                            <input required maxlength="20" autofocus type="text" class="form-control form-control-lg" id="nome" name="nome" title="Por favor, informe o nome do novo tipo de usuário">
                        </div>

                        <div class="form-group form-group-lg text-right mt-5">
                            <input type="hidden" name="token" value="<?= password_hash(TOKEN_SESSAO, PASSWORD_DEFAULT) ?>">
                            <a role="button" href="<?= $_SERVER["REQUEST_URI"] ?>" class="btn btn-lg active btn-link text-primary">Cancelar</a>
                            <button type="submit" class="btn btn-success active text-white btn-lg" name="btnConfirmar">Confirmar <i class="fa fa-check"></i></button>
                        </div>

                    </form>

                </div>

            </div>

            <div class="card border-primary">

                <div class="card-header bg-primary py-3">
                    <h5 class="text-uppercase m-0 text-white text-center text-md-left">Tipos de Usuários Cadastrados</h5>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover m-0">

                            <thead>

                            <tr class="bg-gray-100">

                                <th class="border-0 font-weight-bold text-uppercase text-dark">Nome</th>
                                <th class="border-0 text-center font-weight-bold text-uppercase text-dark">Criado</th>
                                <th class="border-0 text-center font-weight-bold text-uppercase text-dark">Ativado</th>
                                <th class="border-0 text-center font-weight-bold text-uppercase text-dark min-180">Ação</th>

                            </tr>

                            </thead>

                            <tbody class="px-2">

                            <?php

                            if (!empty($lista_registros)) {
                                foreach ($lista_registros as $registro) {
                                    ?>

                                    <tr>

                                        <td class="font-weight-bold text-muted"><?= $registro["tip_nome"] ?></td>
                                        <td class="text-center font-weight-bold text-muted"><?= date("d/m/Y", strtotime($registro["tip_dtcad"])) ?></td>
                                        <td class="text-center font-weight-bold text-muted">
                                            <label class="switch switch-label switch-pill switch-success switch-sm">
                                                <input class="switch-input" type="checkbox" checked="">
                                                <span class="switch-slider" data-checked="" data-unchecked=""></span>
                                            </label>
                                        </td>
                                        <td class="text-center">

                                            <a class="btn btn-primary btn-acao" title="Editar" href="<?= rtrim($_SERVER["REQUEST_URI"], "/") . "/edit/" . $registro["tip_id"] ?>">

                                                <i class="material-icons">edit</i>

                                            </a>

                                            <button class="btn btn-danger btn-acao" title="Excluir">

                                                <i class="material-icons">close</i>

                                            </button>

                                        </td>

                                    </tr>

                                    <?php

                                }
                            }

                            ?>

                            </tbody>

                        </table>

                    </div>

                    <?php
                        paginacao($paginacao->total_registros,$paginacao->registros_paginas,$paginacao->pagina_atual,$paginacao->range_paginas)
                    ?>

                </div>

            </div>

        </div>

    </div>
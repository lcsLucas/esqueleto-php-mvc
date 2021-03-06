<?php
namespace App\model;

use App\dao\TipoUsuarioDao;

if (! defined('ABSPATH'))
    die;

class TipoUsuario extends TipoUsuarioDao{
    private $id;
    private $nome;
    private $data_cadastro;
    private $ativo;
    private $flag_adm;
    private $menus;

    /**
     * TipoUsuario constructor.
     * @param $nome
     * @param $ativo
     */
    public function __construct($nome='', $ativo='0')
    {
        parent::__construct($this);
        $this->nome = $nome;
        $this->ativo = $ativo;
        $this->flag_adm = '0';
        $this->data_cadastro = date('Y-m-d');
        $this->menus = array();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * @param mixed $data_cadastro
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
    }

    /**
     * @return mixed
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param mixed $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * @return mixed
     */
    public function getFlagAdm()
    {
        return $this->flag_adm;
    }

    /**
     * @param mixed $flag_adm
     */
    public function setFlagAdm($flag_adm)
    {
        $this->flag_adm = $flag_adm;
    }

	/**
	 * @return array
	 */
	public function getMenus(): array
	{
		return $this->menus;
	}

	/**
	 * @param array $menus
	 */
	public function setMenus(array $menus): void
	{
		$this->menus = $menus;
	}

    public function getRetorno() {
        return parent::getRetorno();
    }

    public function cadastrar() {
		$result = false;

		if ($this->conectar()) {
			$this->beginTransaction();

			$result = $this->cadastrarDAO();

			if ($result && !empty($this->menus)) {
				$total = count($this->menus);

				for ($i = 0; $i < $total && $result; $i++)
					$result = $this->cadastrarPermissaoTipoDAO($this->menus[$i]);

				if (!$result)
					$this->setRetorno('Não foi possível definir as permissões para esse tipo de usuário', true, false);

			}

			$this->commitar($result);
		}

		return $result;

    }

    public  function paginacao($incio, $fim) {
        return $this->limiteRegistroDAO($incio, $fim);
    }

    public function totalRegistros() {
        return $this->totalRegistrosDAO();
    }

    public function carregar() {

        $result = $this->carregarDAO();

        if (!empty($result)) {

            $this->nome = $result['tip_nome'];
            return true;

        }

        return false;
    }

    public function alterar() {
        $result = false;

        if ($this->conectar()) {
            $this->beginTransaction();

            $result = $this->alterarDAO();

            if ($result) { //&& $this->id !== 1

                $result = $this->excluirPermissoesTipoDAO();

                if ($result && !empty($this->menus)) {
                    $total = count($this->menus);

                    for ($i = 0; $i < $total && $result; $i++)
                        $result = $this->cadastrarPermissaoTipoDAO($this->menus[$i]);

                    if (!$result)
                        $this->setRetorno('Não foi possível definir as permissões para esse tipo de usuário', true, false);

                }

            }

            $this->commitar($result);
        }

        return $result;
    }
    public function carregarTipoUsuario() {
        $retorno = $this->carregarTipoUsuarioDAO($_SESSION['_idusuario']);

        if (!empty($retorno)) {
            $this->id = $retorno['tip_id'];
            $this->flag_adm = $retorno['tip_administrador'];
            return true;
        }

        return false;
    }

    public function alterarStatus() {
        return $this->alterarStatusDAO();
    }

    public function excluir() {

        $retorno = $this->excluirDAO();

        if (!empty($retorno))
            return true;

        return false;
    }

    public function listarTodos() {
        return $this->listarTodosDAO();
    }

}
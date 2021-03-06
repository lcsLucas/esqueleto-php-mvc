<?php


	namespace App\model;

	use App\dao\MenuDao;

    if (! defined('ABSPATH'))
        die;

	class Menu extends MenuDao
	{
		private $id;
		private $nome;
		private $url;
		private $icone;
		private $ordem;
		private $ativo;
		private $menu_pai;
		private $secao_menu;

		/**
		 * Menu constructor.
		 * @param $nome
		 * @param $url
		 * @param $ativo
		 * @param $ordem
		 */
		public function __construct($nome='', $url='', $ativo = '0', $ordem=null)
		{
			parent::__construct($this);
			$this->nome = $nome;
			$this->url = $url;
			$this->ativo = $ativo;
			$this->ordem = $ordem;
			$this->icone = null;
			$this->menu_pai = null;
			$this->secao_menu = null;
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
		public function setId($id): void
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
		public function setNome(string $nome): void
		{
			$this->nome = $nome;
		}

		/**
		 * @return string
		 */
		public function getUrl(): string
		{
			return $this->url;
		}

		/**
		 * @param string $url
		 */
		public function setUrl(string $url): void
		{
			$this->url = $url;
		}

		/**
		 * @return null
		 */
		public function getIcone()
		{
			return $this->icone;
		}

		/**
		 * @param null $icone
		 */
		public function setIcone($icone): void
		{
			$this->icone = $icone;
		}

		/**
		 * @return null
		 */
		public function getOrdem()
		{
			return $this->ordem;
		}

		/**
		 * @param null $ordem
		 */
		public function setOrdem($ordem): void
		{
			$this->ordem = $ordem;
		}

		/**
		 * @return null
		 */
		public function getMenuPai()
		{
			return $this->menu_pai;
		}

		/**
		 * @param null $menu_pai
		 */
		public function setMenuPai($menu_pai): void
		{
			$this->menu_pai = $menu_pai;
		}

		/**
		 * @return null
		 */
		public function getSecaoMenu()
		{
			return $this->secao_menu;
		}

		/**
		 * @param null $secao_menu
		 */
		public function setSecaoMenu($secao_menu): void
		{
			$this->secao_menu = $secao_menu;
		}

		/**
		 * @return string
		 */
		public function getAtivo(): string
		{
			return $this->ativo;
		}

		/**
		 * @param string $ativo
		 */
		public function setAtivo(string $ativo): void
		{
			$this->ativo = $ativo;
		}

		public function getRetorno() {
			return parent::getRetorno();
		}

		public  function paginacao($incio, $fim) {
			return $this->limiteRegistroDAO($incio, $fim);
		}

		public function totalRegistros() {
			return $this->totalRegistrosDAO();
		}

		public  function paginacao2($incio, $fim) {
			return $this->limiteRegistroDAO2($incio, $fim);
		}

		public function totalRegistros2() {
			return $this->totalRegistrosDAO2();
		}

		public function cadastrar() {
			return $this->cadastrarDAO();
		}

		public function alterarStatus() {
			return $this->alterarStatusDAO();
		}

		public function carregar() {
			return $this->carregarDAO();
		}

		public function alterar() {
			return $this->alterarDAO();
		}

		public function excluir() {
			return $this->excluirDAO();
		}

		public function listarTodosMenus() {
			return $this->listarTodosMenusDAO();
		}

		public function listarMenusOrdenacao() {
			$retorno = array();
			$result = $this->listarMenusOrdenacaoDAO();

			if (!empty($result)) {

				foreach ($result as $men) {

					$result2 = $this->listarTodosSubMenusPermissaoDAO($men['id']);

					if (!empty($men['idsecao_menu'])) {
						if (!empty($retorno[$men['idsecao_menu']])) {
							$retorno[$men['idsecao_menu']]['menus'][$men['id']]['nome'] = $men['nome'];
							$retorno[$men['idsecao_menu']]['menus'][$men['id']]['submenus'] = $result2;
						} else {
							$retorno[$men['idsecao_menu']] =
								array(
									'nome' =>  $men['nome_secao'],
									'menus' =>
										array(
											$men['id'] =>
												array(
													'nome' => $men['nome'],
													'submenus' => $result2
												)
										)
								);
						}
					} else {
						if (!empty($retorno[0])) {
							$retorno[0]['menus'][$men['id']]['nome'] = $men['nome'];
							$retorno[0]['menus'][$men['id']]['submenus'] = $result2;
						} else {
							$retorno[0] =
								array(
									'nome' =>  $men['nome_secao'],
									'menus' =>
										array(
											$men['id'] =>
												array(
													'nome' => $men['nome'],
													'submenus' => $result2
												)
										)
								);
						}
					}
				}
			}

			/*
			 * carregar essa nova estrutura na view separando por seçãoes os menus
			 */

			return $retorno;
		}

		public function carregarMenusUsuario($idusuario) {
			$retorno = array();
			$result_menus = $this->carregarMenusUsuarioDAO($idusuario);

			if (!empty($result_menus))
			foreach ($result_menus as $result) {

				$index_secao = (!empty($result['id_secao'])) ? $result['id_secao'] : 0;

				if (!empty($result['nome_secao']))
					$retorno[$index_secao]['nome'] = $result['nome_secao'];
				else
					$retorno[$index_secao]['nome'] = '';

				$retorno[$index_secao]['menus'][(int)$result['id']] = array('nome' => $result['nome'], 'icone' => $result['icone'], 'url' => $result['url']);

				$result_submenus = $this->carregarSubMenusUsuario($idusuario, (int)$result['id']);

				if (!empty($result_menus))
					foreach ($result_submenus as $result_sub)
						$retorno[$index_secao]['menus'][(int)$result['id']]['submenus'][] = array('nome' => $result_sub['nome'], 'icone' => $result_sub['icone'], 'url' => $result_sub['url']);

			}

			return $retorno;
		}

		public function listarMenus($idtipo = null) {
			$retorno = array();

            if (!empty($idtipo))
                $result_menus = $this->carregarMenusTipoUsuarioDAO($idtipo);
            else
                $result_menus = $this->listarTodosMenusPermissaoDAO();

            if (!empty($result_menus)) {

                foreach ($result_menus as $id => $men) {

                    $retorno[$men['id']]['nome'] = $men['nome'];
                    $retorno[$men['id']]['ativo'] = $men['status'];

                    if (!empty($idtipo))
                        $result_submenus = $this->carregarSubMenusTipoUsuarioDAO($men['id'], $idtipo);
                    else
                        $result_submenus = $this->listarTodosSubMenusPermissaoDAO($men['id']);

                    if (!empty($result_submenus)) {
                        $retorno[$men['id']]['submenu'][] = $result_submenus;
                    }

                }

            }

			return $retorno;
		}

		public function ordenarMenus($array_menus) {
			$result = false;

			if ($this->conectar()) {
				$this->beginTransaction();

				$i = 0;
				$total = count($array_menus);

				do {
					$result = $this->ordenarMenuDAO($i+1, $array_menus[$i]);
					$i++;
				} while ($result && $i < $total);

				$this->commitar($result);
			}

			return $result;
		}

	}
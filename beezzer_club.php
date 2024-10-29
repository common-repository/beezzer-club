<?php
/*
Plugin Name: Beezzer Club
Plugin URI: http://pt.beezzer.com/about/pluginWordpress/
Description: O plugin Beezzer Club permite que você crie um clube de consumidores/clientes dentro do seu Blog, usando o clube criado no http://pt.beezzer.com.
Author: Beezzer Team
Version: 0.1.0
Author URI: http://pt.beezzer.com/
Generated At: www.wp-fun.co.uk;
*/ 

if (!class_exists('BeezzerClub')) {
    class BeezzerClub {
		
		/**
		 * @var string URL do servidor
		 */
		var $beezzerServer = 'pt.beezzer.com';
		
		/**
		 * @var string Caminho do plugin
		 */
		var $beezzerClubRoot = '/wp-content/plugins/beezzer-club';
		
		/**
		 * @var string The name the options are saved under in the database.
		 */
		var $adminOptionsName = 'BeezzerClub_options';
		
		/**
		 * Variáveis de configuração do Beezzer Club
		 */
		var $adminOptions = array();
		
		/**
		 * @var array Array de strings para serem limpadas
		 */
		var $beezzerQSClean = array();
		
		/**
		 * @var string Nome do arquivo de internacionalização
		 */
		var $i18nFile = 'beezzer_club';
				
		/**
		 * PHP 4 Compatible Constructor
		 */
		function BeezzerClub() {
			$this->__construct();
		}
		
		/**
		 * PHP 5 Constructor
		 */		
		function __construct() {
			add_action('admin_menu', array(&$this, 'add_admin_pages'));
			add_action('init', array(&$this, 'add_scripts'));
			add_action('wp_head', array(&$this, 'add_css'));
			add_action('admin_head', array(&$this, 'add_css'));
			
			add_filter('the_content', array(&$this, 'content_changing_function')); 
			add_filter('wp_head', array(&$this, 'content_noindex')); 

			$this->adminOptions = $this->getAdminOptions();
			
			$this->beezzerQSClean = array(
				'page',
				'add_ticket',
				'add_reply',
				'join_club'
			);
		}
		
		/**
		 * Iinicializa plugin
		 */
		function __init() {
			if (!$this->isClubPage())
				return;
			
			if (isset($_GET['show_ticket'])) {
				if (isset($_GET['add_reply']))
					$this->beezzer_add_reply();
				else
					$this->beezzer_ticket($_GET['show_ticket']);
			}
			else if (isset($_GET['add_ticket']))
				$this->beezzer_add_ticket($_GET['add_ticket']);
			else if (isset($_GET['add_reply']))
				$this->beezzer_add_reply();
			else if (isset($_GET['join_club']))
				$this->beezzer_join_club();
			else
				$this->beezzer_clube();
		}
		
		function __($string, $noPrint = false) {
			if ($noPrint)
				return __($string, $this->i18nFile);
			else
				echo __($string, $this->i18nFile);
		}
		
		/**
		 * Retrieves the options from the database.
		 * @return array
		 */
		function getAdminOptions() {
			$adminOptions = array(
				'beezzer_club' => null,
				'beezzer_email' => null,
				'beezzer_pass' => null
			);

			$savedOptions = get_option($this->adminOptionsName);
			
			if (!empty($savedOptions['beezzer_club']) && !empty($savedOptions['beezzer_email']) && !empty($savedOptions['beezzer_pass'])) {
				foreach ($savedOptions as $key => $option) {
					$adminOptions[$key] = $option;
				}
			}
			update_option($this->adminOptionsName, $adminOptions);
			
			return $adminOptions;
		}
		
		/**
		 * Saves the admin options to the database.
		 */
		function saveAdminOptions() {
			update_option($this->adminOptionsName, $this->adminOptions);
		}
		
		/**
		 * Verifica se está na página "Clube do Blog"
		 */
		function isClubPage() {
			return (trim(the_title('', '', false)) == 'Clube do Blog');
		}
		
		/**
		 * 
		 */
		function content_changing_function($text) {
			$this->__init();
			return $text;
		}
		
		/**
		 * Define tag <meta> para não indexar a página
		 */
		function content_noindex() {
			if ($this->isClubPage()) {
				print('<meta name="robots" content="noindex" />');
			}
		}
		
		function add_admin_pages() {
			if (function_exists('add_submenu_page')) {
				add_submenu_page('plugins.php', 'Configurações do Plugin Beezzer Club', 'Beezzer Club Config', 10, 'Beezzer Club Config', array(&$this, 'output_beezzer_club_config'));
			}
		}
		
		/**
		 * Outputs the HTML for the admin sub page.
		 */
		function output_beezzer_club_config() {
			$errorDataNotComplete = (!$this->adminOptions['beezzer_club'] || !$this->adminOptions['beezzer_email'] || !$this->adminOptions['beezzer_pass']);
			
			if (!$errorDataNotComplete) {
				$req = $this->beezzer_request('http://' . $this->beezzerServer . '/' . $this->adminOptions['beezzer_club'] . '.json');
				$errorAuth = (strpos($req, 'ERROR: Could not authenticate you') !== false);
				
				if (!$errorAuth) {
					$bz = json_decode($req, true);
					$errorClubNotExist = (!is_array($bz) || !isset($bz['produto']['name']));
				}
			}
			
			include('beezzer_club_template_clube_conf.php');
		} 
		
		/**
		 * Tells WordPress to load the scripts
		 */
		function add_scripts() {
			wp_enqueue_script('prototype');
			wp_enqueue_script('beezzer_club_script', $this->beezzerClubRoot . '/js/script.js', array('prototype'), 0.1); 
		}
		
		/**
		 * Adds a link to the stylesheet to the header
		 */
		function add_css() {
			echo '<link rel="stylesheet" href="' . get_bloginfo('wpurl') . $this->beezzerClubRoot . '/css/style.css" type="text/css" media="screen" />'; 
		}
			
		/**
		 * Limpa strings da query_string
		 */
		function beezzer_clean_querystring() {			
			$qs = explode('&', $_SERVER['QUERY_STRING']);
			$aux = array();
			
			foreach($qs as $i => $j) {
				$aux = explode('=', $qs[$i]);
				if (in_array($aux[0], $this->beezzerQSClean)) {
					unset($qs[$i]);
					continue;
				}
				$qs[$i] = implode('=', $aux);
			}
			
			return implode('&', $qs);
		}
		
		/**
		 * Faz requisição pela API do Beezzer
		 */
		function beezzer_request($url, $valuesArr = array()) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			
			$queryArray = array();
			foreach ($valuesArr as $key => $value)
				$queryArray[] = urlencode($key) . '=' . urlencode($value);
			
			$query = implode('&', $queryArray);
		
			curl_setopt($ch, CURLOPT_USERPWD, $this->adminOptions['beezzer_email'] . ":" . $this->adminOptions['beezzer_pass']);
			curl_setopt($ch, CURLOPT_POST, count($valuesArr));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
		
			$result = curl_exec($ch);
			curl_close($ch);
			
			return $result;
		}
		
		/**
		 * Exibe tela de erro de configuração
		 */
		function beezzer_error_conf() {
			$msg = '<h3>' . $this->__('Erro') . '</h3>';
			$msg .= $this->__('Você deve configurar os 3 campos obrigatórios do plugin: Clube, E-mail e Senha.');
			$msg .= '<br />';
			
			echo $msg;
		}
		
		/**
		 * 
		 */
		function beezzer_clube () {
			$page = (isset($_GET['page'])) ? '?page=' . $_GET['page'] : '';			
			$bz = json_decode($this->beezzer_request('http://' . $this->beezzerServer . '/' . $this->adminOptions['beezzer_club'] . '.json' . $page), true);
			
			if (is_array($bz))
				include('beezzer_club_template_clube.php');
			else
				include('beezzer_club_template_erro.php');
		} 
		
		/**
		 * 
		 */
		function beezzer_ticket ($url) {
			$page = (isset($_GET['page'])) ? "?page=" . $_GET['page'] : "";
			$bz = json_decode($this->beezzer_request("$url.json$page"), true);
			
			if (is_array($bz))
				include('beezzer_club_template_ticket.php');
			else
				include('beezzer_club_template_erro.php');
		} 
		
		/**
		 * 
		 */
		function beezzer_add_ticket () {
			$params = array_merge($_POST, array ('product_id' => $this->adminOptions['beezzer_club']));
			$bz = json_decode($this->beezzer_request('http://' . $this->beezzerServer . '/tickets/add.json', $params), true);
			
			$this->beezzer_ticket($bz['ticket']['url']);
		} 
		
		/**
		 * 
		 */
		function beezzer_add_reply () {
			$params = $_POST;
			$bz = json_decode($this->beezzer_request('http://' . $this->beezzerServer . '/tickets/addResposta.json', $params), true);
			
			$this->beezzer_ticket($bz['ticket']['url']);
		}
		
		/**
		 * 
		 */
		function beezzer_join_club () {
			$params = array_merge($_POST, array ('product_id' => $this->adminOptions['beezzer_club']));
			$bz = json_decode($this->beezzer_request('http://' . $this->beezzerServer . '/produtosdeusuarios/apiAdd.json', $params), true);
			
			$this->beezzer_clube();
		}
    }
}

if (class_exists('BeezzerClub')) {
	// Instantiate the class
	$BeezzerClub = new BeezzerClub();
}

?>
<?php
/**
 * FTP Class
 * Paulo McNally - paulomcnally[at]gmail.com
 * 2010-12-22 13:37:58 -0600
 * Connect to FTP
 */
class Ftp
	{
	private $cnx;
	public $host		=	NULL;
	public $port		=	NULL;
	public $timeout		=	NULL;
	public $mode		=	true;
	public $user		=	NULL;
	public $password	=	NULL;
	public $file_list;
	public $new_file_name	=	"";
	
	function __construct( $host, $user, $password, $port = 21, $timeout = 90 )
		{
		$this->host		=	$host;
		$this->user		=	$user;
		$this->password	=	$password;
		$this->port		=	$port;
		$this->timeout	=	$timeout;
		$this->set_connect();
		$this->set_login();
		$this->set_mode();
		}
	
	
	/**
	 * @desc	Abre una conexion FTP
	 * @php		http://alsuave.info/EC50A
	 */
	public function set_connect()
		{
		$this->cnx = ftp_connect( $this->host, $this->port, $this->timeout );
		if( !$this->cnx )
			{
			$this->_error( "Error al crear la conexion con el servidor FTP" );
			}
		}
	
	/**
	 * @desc	Inicia sesion en una conexion FTP
	 * @php		http://alsuave.info/96BB1
	 */
	public function set_login()
		{
		if( !@ftp_login( $this->cnx, $this->user, $this->password ) )
			{
			$this->_error( "Error al autenticar al usuario" );
			}
		}
	
	
	/**
	 * @desc	Activa o desactiva el modo pasivo
	 * @php		http://alsuave.info/955EA
	 */
	public function set_mode()
		{
		if( !@ftp_pasv( $this->cnx, $this->mode ) )
			{
			$this->_error( "Error al activar el modo pasivo" );
			}
		}
	
	
	/**
	 * @desc	Devuelve el nombre del directorio actual
	 * @php		http://alsuave.info/0F43F
	 */
	public function get_pwd()
		{
		return ftp_pwd( $this->cnx );
		}
	
	
	/**
	 * @desc	Cambia el directorio actual en un servidor FTP
	 * @php		http://alsuave.info/0700C
	 */
	public function set_chdir( $directory )
		{
		if( !@ftp_chdir( $this->cnx, $directory ) )
			{
			$this->_error( "Error al cambiar hacia el directorio " . $directory );
			}
		return true;
		}
	
	
	/**
	 * @desc	Uploads a file to the FTP server
	 * @php		http://alsuave.info/917D5
	 */
	public function put( $remote_file, $prefix = "" )
		{
		$this->new_file_name	=	md5( strlen( $remote_file ) . rand( 5, 15 ) ) . "." . end( explode( ".", $remote_file ) );
		if( !ftp_put( $this->cnx , $this->new_file_name , $remote_file, FTP_BINARY ) )
			{
			$this->_error( "Error al intentar subir el archivo" );
			}
		return true;
		}
	
	
	/**
	 * @desc	Devuelve una lista de los archivos que se encuentran en el directorio especificado
	 * @php		http://alsuave.info/6F708
	 */
	public function get_nlist( $directory = NULL )
		{
		$directory = ( is_null( $directory ) ) ? $this->get_pwd() : $directory;
		if( !$this->file_list = @ftp_nlist( $this->cnx , $directory ) )
			{
			$this->_error( "Error al listar los archivos del directorio " . $this->get_pwd() );
			}
		}
	
	
	/**
	 * @desc	Crea un directorio
	 * @php		http://alsuave.info/28268
	 */
	public function make_dir( $directory = "default" )
		{
		$this->get_nlist( );
		if( in_array( $directory, $this->file_list ) )
			{
			return true;
			}
		if( !@ftp_mkdir( $this->cnx , $directory ) )
			{
			$this->_error( "Error al crear el directorio " . $directory );
			}
		return true;
		}
	
	
	/**
	 * @desc	Detiene la ejecuion y muesta un mensaje de error
	 */
	private function _error( $message )
		{
		die( $message );
		}
	}
?>
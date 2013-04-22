photobucket-ftp-php
===================

Simple class to upload images to photobucket via FTP Pro Only


	<?php
	require_once 'ftp.php';
	
	$ftp = new Ftp( "ftp.photobucket.com", "user", "password" );
	if( $ftp->make_dir( "test" ) )
			{
			if( $ftp->set_chdir( "test" ) )
					{
					if( $ftp->put( "girl.png" ) )
							{
							echo "Archivo subido con nombre: " . $ftp->new_file_name;
							}
					}
			}
	//echo $ftp->get_pwd();
	//echo print_r( $ftp->get_nlist( ) );
	?>

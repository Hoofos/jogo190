<?

include( "include/envirorment.inc"  ) ;

// Abre arquivo para escrita.
$fileName = PASTA_DESTINO_XMLFILE . "mp3player.xml" ;

if( !( $xmlFile=fopen( $fileName, "w" ) ) ) {
    print( condRetErrFile ) ;
    exit ;
}

fputs( $xmlFile, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . "\n" ) ;
fputs( $xmlFile, "<player showDisplay=\"yes\" showPlaylist=\"yes\" autoStart=\"no\">" . "\n" ) ;
fputs( $xmlFile, "        <song path=\"" . PASTA_DESTINO_XMLFILE . "\" title=\"\" id=\"" . . "\" />" . "\n" ) ;
fputs( $xmlFile, "</player>" ) ;

fclose( $xmlFile ) ;

?>
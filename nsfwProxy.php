<?php

use ContinuumUniverses\ContinuumNsfwFilter\Hooks;

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
define( 'MW_ENTRY_POINT', 'nsfwProxy' );

$webStartPaths = [];
$scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? '';
$documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';

if ( !is_string( $scriptFilename ) || $scriptFilename === '' ) {
	$scriptFilename = getenv( 'SCRIPT_FILENAME' ) ?: '';
}

if ( !is_string( $documentRoot ) || $documentRoot === '' ) {
	$documentRoot = getenv( 'DOCUMENT_ROOT' ) ?: '';
}

if ( $scriptFilename !== '' ) {
	$webStartPaths[] = dirname( $scriptFilename, 2 ) . '/includes/WebStart.php';
}

if ( $documentRoot !== '' ) {
	$webStartPaths[] = rtrim( $documentRoot, '/' ) . '/includes/WebStart.php';
}

$webStartPaths[] = dirname( __DIR__, 2 ) . '/includes/WebStart.php';

$webStartPath = null;
foreach ( array_unique( $webStartPaths ) as $candidate ) {
	if ( is_file( $candidate ) ) {
		$webStartPath = $candidate;
		break;
	}
}

if ( $webStartPath === null ) {
	throw new RuntimeException( 'Unable to locate MediaWiki includes/WebStart.php for nsfwProxy.php' );
}

require $webStartPath;

Hooks::handleProxyRequest();

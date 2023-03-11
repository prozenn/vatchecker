<?php
/**
 * @since 1.1.0
 */
require_once('../../config/config.inc.php');
require_once('../../init.php');
require_once(dirname(__FILE__).'/vatchecker.php');

if ( Tools::getValue('vatchecker') !== Tools::getToken( 'vatchecker' ) ) {
	die;
}

$vatchecker = new Vatchecker();

$vatNumber = Tools::getValue('vat_number');
$countryId = Tools::getValue('id_country');

$is_eu    = $vatchecker->isEUCountry( $countryId );
$checkVat = $vatchecker->checkVat( $vatNumber, $countryId );
$vatValid = $checkVat['valid'];
$vatError = $checkVat['error'];

$valid = ( true === $vatValid ) || ( Configuration::get( 'VATCHECKER_SKIP_VIES' ) && $vatValid === null );

if ( ! $is_eu ) {
	$valid = null;
}

$return = array(
	'valid' => $valid,
	'error' => $vatError,
	'is_eu' => $is_eu,
);

echo json_encode( $return );
die;

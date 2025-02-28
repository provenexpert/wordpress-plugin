<?php
/**
 * File to handle openssl-tasks.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Crypt;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Plugin\Crypt_Base;

/**
 * Object to handle crypt tasks with OpenSSL.
 */
class OpenSSL extends Crypt_Base {
	/**
	 * Name of the method.
	 *
	 * @var string
	 */
	protected string $name = 'openssl';

	/**
	 * Constructor for this object.
	 */
	protected function __construct() {
		$this->set_hash( get_option( PROVENEXPERT_HASH, '' ) );

		// initially generate a hash if it is empty.
		if ( empty( $this->get_hash() ) ) {
			$hash = hash( 'sha256', wp_rand() );
			$this->set_hash( $hash );
			update_option( PROVENEXPERT_HASH, $this->get_hash() );
		}

		parent::__construct();
	}

	/**
	 * Run encryption of the given text.
	 *
	 * @param string $plain_text Text to encrypt.
	 *
	 * @return string
	 */
	public function encrypt( string $plain_text ): string {
		// bail if no text is given.
		if ( empty( $plain_text ) ) {
			return '';
		}

		// encrypt the given string.
		$cipher         = 'AES-128-CBC';
		$iv_length      = openssl_cipher_iv_length( $cipher );
		$iv             = openssl_random_pseudo_bytes( $iv_length );
		$ciphertext_raw = openssl_encrypt( $plain_text, $cipher, $this->get_hash(), OPENSSL_RAW_DATA, $iv );
		$hmac           = hash_hmac( 'sha256', $ciphertext_raw, $this->get_hash(), true );
		return base64_encode( base64_encode( $iv ) . ':' . base64_encode( $hmac . $ciphertext_raw ) );
	}

	/**
	 * Get openssl-encrypted text.
	 *
	 * @param string $encrypted_text The encrypted text.
	 *
	 * @return string
	 */
	public function decrypt( string $encrypted_text ): string {
		// bail if no text is given.
		if ( empty( $encrypted_text ) ) {
			return '';
		}

		// decrypt the given string.
		$cipher             = 'AES-128-CBC';
		$iv_length          = openssl_cipher_iv_length( $cipher );
		$c                  = base64_decode( $encrypted_text );
		$c_exploded         = explode( ':', $c );
		$iv                 = base64_decode( $c_exploded[0] );
		$iv                 = substr( $iv, 0, $iv_length );
		$c                  = base64_decode( $c_exploded[1] );
		$hmac               = substr( $c, 0, $sha2len = 32 );
		$ciphertext_raw     = substr( $c, $sha2len, strlen( $c ) );
		$original_plaintext = openssl_decrypt( $ciphertext_raw, $cipher, $this->get_hash(), OPENSSL_RAW_DATA, $iv );
		$calc_mac           = hash_hmac( 'sha256', $ciphertext_raw, $this->get_hash(), true );
		if ( $hmac && hash_equals( $hmac, $calc_mac ) ) {
			return $original_plaintext;
		}
		return '';
	}
}

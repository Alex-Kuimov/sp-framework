<?php
class SP_Framework_Mail {
	public static function send( $args = null ) {
		if ( isset( $args['email_to'] ) && isset( $args['email_from'] ) && isset( $args['from'] ) && isset( $args['subject'] ) && isset( $args['message'] ) ) {

			$from       = $args['from'];
			$email_to   = $args['email_to'];
			$email_from = $args['email_from'];
			$subject    = $args['subject'];
			$message    = $args['message'];
			$attach     = isset( $args['attachments'] ) ? $args['attachments'] : '';

			$header_from = 'From: ' . $from . ' <' . $email_from . '>';

			$headers = $header_from . "\r\n";

			//send email
			add_filter(
				'wp_mail_charset',
				function() {
					return 'utf-8';
				}
			);

			add_filter(
				'wp_mail_charset',
				function() {
					return 'text/html';
				}
			);

			wp_mail( $email_to, $subject, $message, $headers, $attach );

			remove_filter(
				'wp_mail_charset',
				function() {
					return 'utf-8';
				}
			);

			remove_filter(
				'wp_mail_charset',
				function() {
					return 'text/html';
				}
			);
		}
	}
}

<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Email;

/**
 * Abstract class BaseEmail.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseEmail {

	const CONTENT_TYPE_HTML = 'Content-Type: text/html; charset=UTF-8';
	const IS_HTML           = true;
	const EMAIL_TYPE        = 'generic';

	/**
	 * Send the Email.
	 *
	 * @since 1.0.0
	 */
	public function send() {

		/**
		 * Filter the recipients email address.
		 *
		 * Note: you can return an array or comma-separated list of email addresses.
		 *
		 * @since 1.0.0
		 *
		 * @param  mixed  $email The recipient's email address.
		 * @param  string $type  LPF's internal email type slug.
		 *
		 * @return mixed  $email The recipient's email address.
		 */
		$recipient = apply_filters( 'lpf_email_recipient', $this->recipient(), static::EMAIL_TYPE );
		$recipient = apply_filters( 'lpf_email_recipient_' . static::EMAIL_TYPE, $recipient, static::EMAIL_TYPE );

		/**
		 * Filter the subject of the email.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $subject The subject of the email.
		 * @param  string $type    LPF's internal email type slug.
		 *
		 * @return string $subject The subject of the email.
		 */
		$subject = apply_filters( 'lpf_email_subject', $this->subject(), static::EMAIL_TYPE );
		$subject = apply_filters( 'lpf_email_subject_' . static::EMAIL_TYPE, $subject, static::EMAIL_TYPE );

		/**
		 * Filter the message of the email.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $message The email's message.
		 * @param  string $type    LPF's internal email type slug.
		 *
		 * @return string $message The email's message.
		 */
		$message = apply_filters( 'lpf_email_message', $this->message(), static::EMAIL_TYPE );
		$message = apply_filters( 'lpf_email_message_' . static::EMAIL_TYPE, $message, static::EMAIL_TYPE );

		/**
		 * Filter the headers of the email.
		 *
		 * Here are some examples of how to customize the email using the headers.
		 * $headers[] = 'From: Me Myself <me@example.net>';
		 * $headers[] = 'Reply-To: Person Name <person.name@example.com>',
		 * $headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';
		 * $headers[] = 'Cc: iluvwp@wordpress.org';
		 *
		 * @since 1.0.0
		 *
		 * @param  array  $headers The array of headers for this email.
		 * @param  string $type    LPF's internal email type slug.
		 *
		 * @return array  $headers The array of headers for this email.
		 */
		$headers = apply_filters( 'lpf_email_headers', $this->construct_headers(), static::EMAIL_TYPE );
		$headers = apply_filters( 'lpf_email_headers_' . static::EMAIL_TYPE, $headers, static::EMAIL_TYPE );

		/**
		 * Filter the attachments of the email.
		 *
		 * @since 1.0.0
		 *
		 * @param  array  $attachments The array of attachments for this email.
		 * @param  string $type        LPF's internal email type slug.
		 *
		 * @return array  $attachments The array of attachments for this email.
		 */
		$attachments = apply_filters( 'lpf_email_attachments', $this->attachments(), static::EMAIL_TYPE );
		$attachments = apply_filters( 'lpf_email_attachments_' . static::EMAIL_TYPE, $attachments, static::EMAIL_TYPE );

		// Send the email.
		return wp_mail( $recipient, $subject, $message, $headers, $attachments );
	}

	/**
	 * Get the recipient's email address.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed An array or comma-separated list of email addresses.
	 */
	abstract protected function recipient();

	/**
	 * Get the email subject.
	 *
	 * @since 1.0.0
	 *
	 * @return string The subject of the email.
	 */
	abstract protected function subject();

	/**
	 * Get the email message.
	 *
	 * @since 1.0.0
	 *
	 * @return string The email's message.
	 */
	abstract protected function message();

	/**
	 * Get the headers.
	 *
	 * @since 1.0.0
	 *
	 * @return array The array of headers for this email.
	 */
	protected function construct_headers() {
		$headers = [];

		if ( true === static::IS_HTML ) {
			$headers[] = static::CONTENT_TYPE_HTML;
		}

		return $headers;
	}

	/**
	 * Get the attachments.
	 *
	 * @since 1.0.0
	 *
	 * @return array The array of attachments for this email.
	 */
	protected function attachments() {
		return [];
	}
}

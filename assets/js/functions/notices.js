export default class LPFNotices {

	/**
	 * Return the HTML for an admin notice. This inherits styles from WordPress' default admin notices.
	 *
	 * @param string type    The type of notice. WordPress supports `success`,`error`,`warning`,`info`
	 * @param string message The message shown in the notice.
	 *
	 * @return string Notice HTML.
	 */
	admin_notice( type, message ) {
		return `<div class="lpf-notice lpf-admin-notice notice notice-${type}"><p>${message}</p></div>`;
	}

	remove_notices() {
		jQuery( '.lpf-notice' ).remove();
	}
}

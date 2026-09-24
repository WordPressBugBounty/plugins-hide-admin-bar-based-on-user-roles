/**
 * Hide Admin Bar settings page.
 *
 * Saves through the existing save_user_roles AJAX action with the same fields as before,
 * so the stored hab_settings option keeps its format.
 */
( function ( $ ) {
	'use strict';

	$( function () {
		var $form = $( '#hab-settings-form' );

		if ( ! $form.length ) {
			return;
		}

		var i18n = ajaxVar.i18n;
		var $hideForAll = $( '#hab-hide-for-all' );
		var $rules = $( '#hab-rules' );
		var $tagList = $form.find( '.hab-tags-list' );
		var $tagInput = $( '#hab-capability-input' );
		var $save = $( '#hab-save' );
		var $spinner = $form.find( '.hab-card-footer .spinner' );
		var $status = $( '#hab-status' );

		// "Hide for everyone" overrides the other rules.
		$hideForAll.on( 'change', function () {
			$rules.prop( 'disabled', this.checked );
		} );

		function capabilities() {
			return $tagList.find( '.hab-tag-label' ).map( function () {
				return $( this ).text();
			} ).get();
		}

		function tag( capability ) {
			var $remove = $( '<button type="button" class="hab-tag-remove"><span aria-hidden="true">&times;</span></button>' )
				.attr( 'aria-label', i18n.removeCapability.replace( '%s', capability ) );

			return $( '<li class="hab-tag"></li>' )
				.append( $( '<span class="hab-tag-label"></span>' ).text( capability ) )
				.append( $remove );
		}

		// Adds every comma-separated capability in text that isn't listed yet.
		function addCapabilities( text ) {
			var existing = capabilities();

			// Plain String/Array methods: jQuery 4 drops $.trim().
			$.each( text.split( ',' ), function ( i, capability ) {
				capability = capability.trim();

				if ( capability && -1 === existing.indexOf( capability ) ) {
					existing.push( capability );
					$tagList.append( tag( capability ) );
				}
			} );
		}

		$tagInput.on( 'keydown', function ( event ) {
			if ( 'Enter' === event.key || ',' === event.key ) {
				event.preventDefault();
				addCapabilities( this.value );
				this.value = '';
			} else if ( 'Backspace' === event.key && '' === this.value ) {
				$tagList.find( '.hab-tag' ).last().remove();
			}
		} ).on( 'input', function () {
			// Pasted lists arrive in one go.
			if ( -1 !== this.value.indexOf( ',' ) ) {
				addCapabilities( this.value );
				this.value = '';
			}
		} ).on( 'blur', function () {
			addCapabilities( this.value );
			this.value = '';
		} );

		$tagList.on( 'click', '.hab-tag-remove', function () {
			$( this ).closest( '.hab-tag' ).remove();
			$tagInput.trigger( 'focus' );
		} );

		$form.find( '.hab-tags' ).on( 'click', function ( event ) {
			if ( ! $( event.target ).closest( '.hab-tag' ).length ) {
				$tagInput.trigger( 'focus' );
			}
		} );

		$form.on( 'submit', function ( event ) {
			event.preventDefault();

			addCapabilities( $tagInput.val() );
			$tagInput.val( '' );

			var roles = $form.find( 'input[name="hab_roles[]"]:checked' ).map( function () {
				return this.value;
			} ).get();

			$save.prop( 'disabled', true );
			$spinner.addClass( 'is-active' );
			$status.removeClass( 'is-success is-error' ).text( i18n.saving );

			$.post( ajaxVar.url, {
				action: 'save_user_roles',
				UserRoles: roles,
				caps: capabilities().join( ',' ),
				disableForAll: $hideForAll.is( ':checked' ) ? 'yes' : 'no',
				forGuests: $( '#hab-hide-for-guests' ).is( ':checked' ) ? 'yes' : 'no',
				hbaNonce: ajaxVar.hba_nonce
			} ).done( function ( response ) {
				if ( 'Success' === response ) {
					$status.addClass( 'is-success' ).text( i18n.saved );
				} else {
					$status.addClass( 'is-error' ).text( i18n.error );
				}
			} ).fail( function () {
				$status.addClass( 'is-error' ).text( i18n.error );
			} ).always( function () {
				$save.prop( 'disabled', false );
				$spinner.removeClass( 'is-active' );
			} );
		} );

		$( '#hab-reset' ).on( 'click', function ( event ) {
			if ( ! window.confirm( i18n.confirmReset ) ) {
				event.preventDefault();
			}
		} );

		$( '#hab-review' ).on( 'click', '.hab-review-dismiss', function () {
			$.post( ajaxVar.url, {
				action: 'hab_dismiss_review_banner',
				dismiss_type: $( this ).data( 'dismiss' ),
				nonce: ajaxVar.review_nonce
			} );
			$( '#hab-review' ).slideUp( 200 );
		} );
	} );
}( jQuery ) );

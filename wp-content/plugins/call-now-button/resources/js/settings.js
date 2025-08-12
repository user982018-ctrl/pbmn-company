// Note: IDE marks this as unused, but it is used by settings-edit.php ("Delete API key")
function cnb_delete_apikey() {
    const apiKeyField = jQuery(".call-now-button-plugin #cnb_api_key")
    apiKeyField.prop("type", "hidden");
    apiKeyField.prop("value", "delete_me");
    apiKeyField.removeAttr("disabled");

    // Ensure we use the exact verbiage of the Submit button
    const saveVal = apiKeyField.parents('.cnb-container').find('#submit').val();
    jQuery('.call-now-button-plugin #cnb_api_key_delete').replaceWith("<p>Click <strong>"+saveVal+"</strong> to disconnect your account.</p>")

    // Present the default behavior of this submit button (since it needs to be actioned on by the *actual* submit button
    return false;
}

const cnb_tally_deactivate_premium_form_id = 'wA7d2z'

function cnb_ask_for_feedback_disable_cloud() {
    const ele = jQuery('#cnb_cloud_enabled')
    const isChecked = ele.is(':checked')
    if (isChecked) {
        ele.on('click', () => {
            const isChecked = ele.is(':checked')
            const options = {
                width: 350,
                hideTitle: 1,
                emoji: {
                    text: '😢',
                    animation: 'heart-beat'
                },
                autoClose: 5000,
                hiddenFields: {
                    wordPressUrl: window.location.href,
                    userId: jQuery('#cnb_user_id').text()
                }
            }
            // Check if Tally actually is loaded
            if (Tally) {
                if (!isChecked) {
                    Tally.openPopup(cnb_tally_deactivate_premium_form_id, options)
                } else {
                    Tally.closePopup(cnb_tally_deactivate_premium_form_id)
                }
            }
        })
    }
}

/**
 * Disable the Cloud inputs when it is disabled (but only on the settings screen,
 * where that checkbox is actually visible)
 */
function cnb_disable_api_key_when_cloud_hosting_is_disabled() {
    const ele = jQuery('#cnb_cloud_enabled');
    if (ele.length) {
        jQuery('.when-cloud-enabled :input').prop('disabled', !ele.is(':checked'));
    }
}

function init_settings() {
    jQuery("#cnb_email_activation_alternate_form").hide()
}

/**
 * Displays a link to a page with tips to fix "Not Working" issues to prevent users from disabling cloud
 */
function cnb_show_tips_when_deactivating() {
  const ele = jQuery("#cnb_cloud_enabled")
  const isChecked = ele.is(':checked')
  if (isChecked) {
    ele.on('click', () => {
        const isChecked = ele.is(':checked')
        if (!isChecked) {
            jQuery("#cnb_not_working_tips").css('display','block');
        } else {
            jQuery("#cnb_not_working_tips").hide();
        }
    })
  }
}

function add_onclick_cnb_user_storage_type() {
    jQuery('.cnb-switch-storage-type').on('click', cnb_user_storage_type)
}
function cnb_user_storage_type() {
    const switchingTo = jQuery(this).data('storage-type')
    const data = {
        'action': 'cnb_set_user_storage_solution',
        'storage_type': switchingTo,
        '_ajax_nonce': jQuery(this).data('wpnonce'),
    }

    jQuery(this).prop('disabled', true)
    jQuery('.cnb-switch-storage-type-result').text("Switching to " + switchingTo + ".").removeClass('hidden').removeClass('notice-success').addClass('notice-info')
    jQuery.post(ajaxurl, data)
        .done((result) => {
            const switchedTo = result.settings.fileStorageImplementation
            jQuery('.cnb-switch-storage-type-result').text("Successfully switched to " + switchedTo + '. Refresh the page to see the new data.').removeClass('notice-info').addClass('notice-success')
        })

    return false
}

function cnb_add_domain_alias() {
    jQuery('#add-alias-button').on('click', () => {
        // Add a new input to #add-alias-div with the value of the add-alias-input text input
        const alias = jQuery('#add-alias-input').val()?.trim().toLowerCase()
        const escapedAlias = jQuery('<div>').text(alias).html();

        if (!escapedAlias) return

        jQuery('[data-add-domain-notice]').remove()

        // Validate the new alias
        // Should not start with http:// or https://
        // Should not contain a / or a space
        if (escapedAlias.startsWith('http://') || escapedAlias.startsWith('https://')) {
            jQuery('#add-alias-div').append('<div class="notice notice-error notice-inline" data-add-domain-notice><p>Invalid domain alias <strong>'+escapedAlias+'</strong>. Please remove the protocol.</p></div>')
            return
        }
        if (alias.includes('/') || alias.includes(' ')) {
            jQuery('#add-alias-div').append('<div class="notice notice-error notice-inline" data-add-domain-notice><p>Invalid domain alias <strong>'+escapedAlias+'</strong>. Please do not use a path or spaces in your domain name.</p></div>')
            return
        }

        jQuery('#add-alias-div').append('<input type="hidden" name="domain[aliases][]" value="' + escapedAlias + '" readonly>')
        jQuery('#add-alias-div').append('<div class="notice notice-success notice-inline"><p>Domain <strong>' + escapedAlias + '</strong> added. Click "Save Changes" to make this permanent.</p></div>')
        jQuery('#add-alias-input').val('')
    })

    jQuery('input[name="delete-alias-button"]').on('click', (e) => {
        console.log(e)
        jQuery(e.currentTarget).parent().remove()
    })
}

function cnb_setup_switch_to_yearly() {
    // Handle upgrade to yearly button click
    jQuery('#cnb-upgrade-to-yearly').on('click', function() {
        const $button = jQuery(this);
        const $result = jQuery('.cnb-upgrade-to-yearly-result');
        const subscriptionId = $button.data('subscription-id');

        // Show confirmation dialog
        if (!confirm('Switch to annual billing?\n\n' +
            '• You will be charged immediately\n' +
            '• Remaining days from your monthly plan will be credited\n' +
            '• Please ensure your payment method is up to date with sufficient funds to avoind service disruption\n\n' +
            'Do you want to proceed?')) {
            return;
        }

        // Disable button and show loading state
        $button.prop('disabled', true).val('Upgrading...');
        $result.removeClass('hidden notice-success notice-error').addClass('notice-info')
            .html('<p>Processing your switch to yearly billing...</p>');

        // Make AJAX call
        jQuery.post(ajaxurl, {
            action: 'cnb_upgrade_to_yearly',
            subscriptionId: subscriptionId
        }, function(response) {
            if (response.success === true) {
                $result.removeClass('notice-info').addClass('notice-success')
                    .html('<p>Your subscription was successfully switched to the annual plan!</p>');
                $button.hide();
            } else {
                $result.removeClass('notice-info').addClass('notice-error')
                    .html('<p>Error: ' + (response.data.message || 'Failed to switch to yearly billing. Please try again.') + '</p>');
                $button.prop('disabled', false).val('Switch to Yearly Billing');
            }
        }).fail(function() {
            $result.removeClass('notice-info').addClass('notice-error')
                .html('<p>Error: Failed to communicate with the server. Please try again.</p>');
            $button.prop('disabled', false).val('Switch to Yearly Billing');
        });
    });
}

jQuery(() => {
    init_settings();
    cnb_disable_api_key_when_cloud_hosting_is_disabled()
    cnb_ask_for_feedback_disable_cloud() //temp disabled
    cnb_show_tips_when_deactivating()
    add_onclick_cnb_user_storage_type()
    cnb_add_domain_alias()
    cnb_setup_switch_to_yearly()
})

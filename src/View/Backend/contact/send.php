<?php

!defined( 'WPINC ' ) or die;

/**
 * Build email template
 *
 * @package    Triangle
 * @subpackage Triangle/EmailTemplate
 */
?>
<div style="display:none;">
    <!--Start: Juice-->
    <div id="juice_err"></div>
    <textarea id="template_html" cols="30" rows="10"><?= $template->build() ?></textarea>
    <textarea id="template_standard" cols="30" rows="10"></textarea>
    <!--End: Juice-->
    <input type="hidden" id="contact_users" value="<?= $params['field_users']  ?>">
    <input type="hidden" id="contact_from_name" value="<?= $params['field_from_name']  ?>">
    <input type="hidden" id="contact_from_email" value="<?= $params['field_from_email']  ?>">
    <input type="hidden" id="contact_email_subject" value="<?= $params['field_email_subject']  ?>">
</div>

<?= $this->loadContent('Element.loading', [
    'id' => 'loading-contact-send'
]) ?>
<h1 id="email_status">Your email has been send!</h1>
<script type="text/javascript">
    var counter = 0;
    setTimeout(function(){ counter++; triangle_send_email(); }, 1000);
    function triangle_send_email() {
        let template_standard = jQuery('#template_standard').val();
        if (template_standard) { /** Send the email */
            jQuery.ajax({
                method: 'POST',
                url: 'admin-ajax.php',
                data: {
                    'action'    : 'triangle-send',
                    'template'  : template_standard,
                    'users'     : jQuery('#contact_users').val(),
                    'from'      : {
                        'name'      : jQuery('#contact_from_name').val(),
                        'email'     : jQuery('#contact_from_email').val(),
                    },
                    'subject'   : jQuery('#contact_email_subject').val(),
                },
            }).done(function (response) {
                if(response){
                    jQuery('#loading-contact-send').hide();
                    jQuery('#email_status').show();
                }
            }).fail(function(){
                console.log('SEND FAILED');
            });
        } else if (counter < 10) { setTimeout(function(){ counter++; triangle_send_email(); }, 1000);
        } else { console.log('BUILD FAILED'); }
    }
</script>
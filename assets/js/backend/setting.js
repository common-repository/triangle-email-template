    /**
     * Init page
     * */
    jQuery('.select2').select2();
    setTimeout(function(){
        animate('#form-result','animated fadeOut').hide();
    },3000);

    /**
     * Validate form before submission
     * */
    jQuery('#setting-form').submit(function(e){
        let validation = { status:true };
        let smtp = jQuery('#field_option_smtp').attr('checked');
        if(smtp){
            validation = validate_form({
                required: ['field_option_smtp_encryption', 'field_option_smtp_host', 'field_option_smtp_port', 'field_option_smtp_username', 'field_option_smtp_password'],
                types: {'field_option_smtp_port': 'number'},
            }, jQuery(this).serializeArray())
        }
        if(!validation.status){
            jQuery('#form-message').html(validation.message);
            animate('#form-message', 'animated flash').show();
            e.preventDefault();
        }
    });
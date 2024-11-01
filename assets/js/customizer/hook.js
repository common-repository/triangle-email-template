/**
 * Trigger & Hooks
 * */
(function( $ ) {

    /**
     * Publish button hook
     * */
    $('#customize-save-button-wrapper #save').on('click', function(){
        /** Ajax Param */
        let params = { action : 'triangle-customizer-save' };
            params = {...params, ...customizerSetting};
        /** Send Ajax */
        return $.ajax({
            method: 'POST',
            url: 'admin-ajax.php',
            data: params,
        }).done(function (response) {
            console.log('SETTING SAVED!');
        }).fail(function(){
            console.log('Something went wrong.');
        });
    });

    /**
     * @setting @id : setting_triangle_background
     * */
    wp.customize( 'setting_triangle_background', function( value ) {
        value.bind( function( newval ) {
            if( newval.length ){
                $('#customize-preview iframe').contents().find('body').css('background-color', `${newval}`);
                customizerSetting.setting_triangle_background = newval;
            }
        } );
    } );

    /**
     * @setting @id : setting_triangle_container_width
     * */
    $(document).on('input', '#control_triangle_container_width', function(){
        $(this).parent().find('.triangle_range_value').html( $(this).val() );
    });
    wp.customize( 'setting_triangle_container_width', function( value ) {
        value.bind( function( newval ) {
            if( newval.length ){
                $('#customize-preview iframe').contents().find('#builder_dom').css('max-width', `${newval}px`);
                customizerSetting.setting_triangle_container_width = newval;
            }
        } );
    } );

    /**
     * @setting @id : setting_triangle_css
     * */
    wp.customize( 'setting_triangle_css', function( value ) {
        value.bind( function( newval ) {
            if( newval.length ){
                let html = `<style>${newval}</style>`;
                $('#customize-preview iframe').contents().find('#triangle_template_css').html(html);
                customizerSetting.setting_triangle_css = newval;
            }
        } );
    } );

}) ( jQuery );

/**
 * Initialize Ace JS Editor
 * */
function init_editor(option){
    if(!window.editor) window.editor = {};
    let editor = (window.editor[option.id]) ? window.editor[option.id] : undefined;
    /** Load ACE Js */
    // if(editor) editor.destroy();
    editor = ace.edit(`template-editor-${option.id}`);
    editor.session.setMode(option.mode);
    editor.setOption("enableEmmet", true);
    editor.setOption("maxLines", "Infinity");
    editor.getSession().setUseWorker(false);
    /** Template Editor Text Area Script */
    textarea = jQuery(`#${option.id}`);
    editor.getSession().setValue(textarea.val());
    editor.getSession().on('change', function () {
        textarea.val(editor.getSession().getValue());
        textarea.change();
    });
    /** Set the editor */
    window.editor[option.id] = editor;
}
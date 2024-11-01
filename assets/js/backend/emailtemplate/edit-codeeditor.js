    /**
     * Init page script
     * @emailtemplate
     * */
    var elements = {};
    load_page();

    /**
     * Init code editor
     * */
    function init_editor(element){
        /** Load ACE Js */
        if(window.editor) window.editor.destroy();
        window.editor = ace.edit("template-editor");
        window.editor.session.setMode(elements[element].mode);
        window.editor.setOption("enableEmmet", true);
        window.editor.setOption("maxLines", "Infinity");
        window.editor.getSession().setUseWorker(false);
        /** Template Editor Text Area Script */
        if(window.textarea) {
            window.editor.getSession().setValue(window.textarea.val());
            window.editor.getSession().on('change', function () {
                window.textarea.val(window.editor.getSession().getValue());
            });
        }
    }

    /**
     * Load page, request data to API
     * */
    function load_page(){
        jQuery.ajax({
            method: 'POST',
            url: 'admin-ajax.php',
            dataType : "json",
            data: {
                'action'    : 'triangle-section-codeeditor',
                'args'      : {
                    'post_id' : window.trianglePlugin.screen.post.ID,
                    'post_name' : window.trianglePlugin.screen.post.post_name,
                }
            },
            success: function(data){
                load_page_elements(data);
                trigger_template_elements();
            }
        });
    }

    /**
     * Load page element, unhide element, do animation, load fields
     * */
    function load_page_elements(data){
        animate('.loading-page', 'animated fadeOut').hide();
        animate('.container', `animated ${window.trianglePlugin.options.animation_content}`).show();
        jQuery('#template-elements').select2({data: data.templates});
        let htmlPreview;
        data.templates.map((template) => {
            template.children.map((children) => {
                let html = `<textarea name="template_${children.id}" id="template_${children.id}" class="element_fields" cols="10">${children.value}</textarea>`;
                htmlPreview = children.value[0];
                jQuery('#template-fields').append(html);
                elements[children.id] = children;
            });
        });
    }

    /**
     * Trigger Template Elements
     * */
    jQuery(document).on("change", "#template-elements", trigger_template_elements);
    function trigger_template_elements(){
        let element = jQuery("#template-elements").val();
        window.textarea = jQuery(`#template_${element}`);
        init_editor(element);
    }
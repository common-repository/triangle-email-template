jQuery(document).ready(function($){
/**
 * jQuery UI Sortable
 * */
    /** Init Sortable */
    cleanSettingScript();
    function initSortable() {
        /** Draggable Row */
        $('#builder_dom').sortable({
            helper:'#row-action-move',
            items: "> div",
            tolerance: 'pointer',
            scrollSensitivity:40,

            stop: function( event, ui ) {
                renderGrid();
            }
        }).disableSelection();
        /** Draggable Element */
        $( ".row-content" ).sortable({
            items: "> div",
            connectWith: ".row-content",
            tolerance: 'pointer',

            scrollSensitivity:40,
            forcePlaceholderSize:true,
            revert: true,

            start: function( event, ui ) {
                /** Placeholder Grid */
                let element = ui.item;
                let height = $(element).css('height');
                $('.ui-sortable-placeholder').css('height', height);
                $('.row').addClass('row-dropzone');
                $('.element').addClass('element-highlight');
            },
            stop: function( event, ui ) {
                /** Placeholder Grid */
                $('.row').removeClass('row-dropzone');
                $('.element').removeClass('element-highlight');
                /** Render Grid */
                renderGrid();
            }
        }).disableSelection();
    }

/**
 * Grid Functions
 * */
    /** Add new element */
    $(document).on('click', '#btn-add-new-element', function(){
        var row = document.createElement('div');
            row.innerHTML = $('#new-row').html();
            row.className = "row ui-sortable-handle";
        $('.row, .element').removeAttr('id').removeAttr('style');
        $('#builder_dom').append(row);
        cleanSettingScript();
    });

    /** Clone Row */
    $(document).on('click', '#row-action-clone', function(){
        /** Clone Row */
        let row_content = $(this).parent().parent().parent();
        var row = document.createElement('div');
            row.innerHTML = $(row_content).html();
            row.className = "row ui-sortable-handle";
        $('.row, .element').removeAttr('id').removeAttr('style');
        $('#builder_dom').append(row);
        cleanSettingScript();
    });

    /** Remove row from the grid */
    $(document).on('click', '#row-action-remove', function(){
        let row = $(this).parent().parent().parent();
        $.confirm({
            title: 'Delete Row',
            content: 'are you sure you want to delete the row?',
            theme: 'material',
            icon: 'fas fa-trash',
            escapeKey: 'cancel',
            type: 'red',
            buttons: {
                confirm: function () {
                    $(row).remove();
                    cleanSettingScript();
                },
                cancel: function () {},
            }
        });
    });

    /** Clone Element */
    $(document).on('click', '#element-action-clone', function(){
        let row_content = $(this).parent().parent().parent();
        let element = $(this).parent().parent();
        $('#element-setting', element).remove();
        $(element).clone().appendTo(row_content);
        cleanSettingScript();
    });

    /** Remove element from the grid */
    $(document).on('click', '#element-action-remove', function(){
        let element = $(this).parent().parent();
        $.confirm({
            title: 'Delete Element',
            content: 'are you sure you want to delete the element?',
            theme: 'material',
            icon: 'fas fa-trash',
            escapeKey: 'cancel',
            type: 'red',
            buttons: {
                confirm: function () {
                    $(element).remove();
                    cleanSettingScript();
                },
                cancel: function () {},
            }
        });
    });

/**
 * Setting Functions
 * */
    /** Row Setting */
    var rowSetting = $('#row-setting');
    $(document).on('mouseenter', `.email-grid .row`, function(){
        $(`.row-content`, this).before(rowSetting);
    }).on('mouseleave', `.email-grid .row`, function(){
        rowSetting.remove();
    });

    /** Element Setting */
    var elementSetting = $('#element-setting');
    $(document).on('mouseenter', `.email-grid .element`, function(){
        $(`.element-content`, this).before(elementSetting);
    }).on('mouseleave', `.email-grid .element`, function(){
        elementSetting.remove();
    });

    /** Row Margin and Padding */
    var rowLinked = { margin: true, padding: true };
    $(document).on('click', '#row-margin-linked', function(){ toggleMarginorPaddingLinked('row', 'margin'); });
    $(document).on('click', '#row-padding-linked', function(){ toggleMarginorPaddingLinked('row', 'padding'); });
    $(document).on('keyup', '.row-margin', function(){ setMarginorPaddingLinkedValue('row', 'margin', $(this).val() ); });
    $(document).on('keyup', '.row-padding', function(){ setMarginorPaddingLinkedValue('row', 'padding', $(this).val() ); });

    /** Element Margin and Padding */
    var elementLinked = { margin: true, padding: true };
    $(document).on('click', '#element-margin-linked', function(){ toggleMarginorPaddingLinked('element', 'margin'); });
    $(document).on('click', '#element-padding-linked', function(){ toggleMarginorPaddingLinked('element', 'padding'); });
    $(document).on('keyup', '.element-margin', function(){ setMarginorPaddingLinkedValue('element', 'margin', $(this).val() ); });
    $(document).on('keyup', '.element-padding', function(){ setMarginorPaddingLinkedValue('element', 'padding', $(this).val() ); });

    /** JConfirm - Row Setting */
    $(document).on('click', '#row-action-setting', function(){
        var row = $(this).parent().parent().parent();
        var rowContent = $('.row-content', row);
        var rowSetting = {
            background : rowContent.css('background-color'),
            rowMargin : getMarginorPadding(rowContent, 'margin'),
            rowPadding : getMarginorPadding(rowContent, 'padding'),
            linked : {
                margin: (rowContent.attr('data-margin-linked')==undefined) ? true : (rowContent.attr('data-margin-linked')=='true'),
                padding: (rowContent.attr('data-padding-linked')==undefined) ? true : (rowContent.attr('data-padding-linked')=='true'),
            }
        };
        $.confirm({
            title: 'Row',
            icon: 'fas fa-cog',
            columnClass: 'col-sm-12',
            theme: 'material',
            closeIcon: 'cancel',
            escapeKey: 'cancel',
            backgroundDismiss: true,
            animation: 'scale',
            type: 'purple',
            offsetTop: 40,
            content: function () {
                var self = this;
                return $.ajax({
                    method: 'POST',
                    url: 'admin-ajax.php',
                    data: {
                        'action'    : 'triangle-builder-row-setting',
                    },
                }).done(function (response) {
                    self.setContent(response);
                    setTimeout(function(){
                        /** Set Attributes */
                        let defaultClass = ['row', 'ui-sortable', 'ui-sortable-handle'];
                        let attrClass = row.attr('class')
                            .replace(/ +(?= )/g,'').split(' ')
                            .filter((name) => { return !defaultClass.includes(name) })
                            .join(' ');
                        let attributes = { id: row.attr('id'), class: attrClass };
                        if(attributes.id) $('#row-id').val(attributes.id);
                        if(attributes.class) $('#row-class').val(attributes.class);

                        /** Set Style */
                        initColorPicker({ 'default' : rowSetting.background });
                        setMarginorPaddingValue('row', 'margin', rowSetting.rowMargin);
                        setMarginorPaddingValue('row', 'padding', rowSetting.rowPadding);

                        /** Set Linked */
                        if(rowSetting.linked.margin==false) toggleMarginorPaddingLinked('row', 'margin');
                        if(rowSetting.linked.padding==false) toggleMarginorPaddingLinked('row', 'padding');
                    }, 300);
                }).fail(function(){
                    self.setContent('Something went wrong.');
                });
            },
            buttons: {
                save: function () {
                    /** Save Attributes */
                    let attributes = {
                        id: $('#row-id').val().replace(/ {1,}/g," "),
                        class: $('#row-class').val().replace(/ {1,}/g," ")
                    };
                    row.attr('id', attributes.id);
                    row.attr('class', `row ui-sortable ${attributes.class}`);

                    /** Save Style */
                    rowContent.css('background', $('.pcr-button').css('color'));
                    setMarginorPadding(rowContent, 'row', 'margin');
                    setMarginorPadding(rowContent, 'row', 'padding');

                    /** Save Linked */
                    rowContent.attr('data-margin-linked', rowLinked.margin);
                    rowContent.attr('data-padding-linked', rowLinked.padding);

                    /** Clean Script */
                    setTimeout(cleanSettingScript, 500);
                },
                cancel: function () {
                    /** Clean Script */
                    setTimeout(cleanSettingScript, 500);
                },
            }
        });
    });

    /** JConfirm - Element Setting */
    $(document).on('click', '#element-action-setting', function(){
        let element = $(this).parent().parent();
        let elementContent = $('.element-content', element);
        let elementClass = element.attr('class').split(' ');
        var elementSetting = {
            column : elementClass.filter((value) => (value.includes('col-sm-')) ),
            rowMargin : getMarginorPadding(elementContent, 'margin'),
            rowPadding : getMarginorPadding(elementContent, 'padding'),
            linked : {
                margin: (elementContent.attr('data-margin-linked')==undefined) ? true : (elementContent.attr('data-margin-linked')=='true'),
                padding: (elementContent.attr('data-padding-linked')==undefined) ? true : (elementContent.attr('data-padding-linked')=='true'),
            }
        };
        elementSetting.column = (elementSetting.column[0]) ? elementSetting.column[0].replace('col-sm-','') : '12';
        $.confirm({
            title: 'Element',
            columnClass: 'col-sm-12',
            icon: 'fas fa-cog',
            theme: 'material',
            closeIcon: 'cancel',
            escapeKey: 'cancel',
            backgroundDismiss: true,
            animation: 'scale',
            type: 'purple',
            offsetTop: 40,
            content: function () {
                var self = this;
                return $.ajax({
                    method: 'POST',
                    url: 'admin-ajax.php',
                    data: {
                        'action'    : 'triangle-builder-element-setting',
                        'column'    : elementSetting.column,
                    },
                }).success(function (response) {
                    /** Set Content */
                    self.setContent(response);
                    $.ajax({
                        method: 'POST',
                        url: 'admin-ajax.php',
                        data: {
                            'action'    : 'triangle-editor',
                            'content'   : elementContent.html(),
                        },
                    }).success(function(response){
                        /** Set TinyMCE */
                        $('#element-editor').html(response);
                        tinymce.init( tinymce.extend( {}, tinyMCEPreInit.mceInit[ 'wp_element_editor' ] ) );
                        $('#wp-wp_element_editor-wrap').removeClass(`tmce-active html-active`).addClass(`tmce-active`);

                        /** Set Attributes */
                        let defaultClass = ['element', 'ui-sortable', 'ui-sortable-handle', `col-sm-${elementSetting.column}`];
                        let attrClass = element.attr('class')
                            .replace(/ +(?= )/g,'').split(' ')
                            .filter((name) => { return !defaultClass.includes(name) })
                            .join(' ');
                        let attributes = { id: element.attr('id'), class: attrClass };
                        if(attributes.id) $('#element-id').val(attributes.id);
                        if(attributes.class) $('#element-class').val(attributes.class);

                        /** Set Style */
                        $('#grid-column-size').val(elementSetting.column);
                        setMarginorPaddingValue('element', 'margin', elementSetting.rowMargin);
                        setMarginorPaddingValue('element', 'padding', elementSetting.rowPadding);

                        /** Set Linked */
                        if(elementSetting.linked.margin==false) toggleMarginorPaddingLinked('element', 'margin');
                        if(elementSetting.linked.padding==false) toggleMarginorPaddingLinked('element', 'padding');
                    });
                }).fail(function(){
                    self.setContent('Something went wrong.');
                });
            },
            buttons: {
                save: function () {
                    /** Editor */
                    let mode = ($('#wp-wp_element_editor-wrap').hasClass(`tmce-active`)) ? 'visual' : 'text';
                    let value = (mode=='visual') ? tinymce.editors.wp_element_editor.getContent() : wpautop($('#wp_element_editor').val());
                    tinymce.execCommand('mceRemoveControl', true, 'wp_element_editor');

                    /** Save Attributes */
                    let attributes = {
                        id: $('#element-id').val().replace(/ {1,}/g," "),
                        class: $('#element-class').val().replace(/ {1,}/g," "),
                    };
                    element.attr('id', attributes.id);
                    element.attr('class', `element ui-sortable-handle col-sm-${$('#grid-column-size').val()} ${attributes.class}`);

                    /** Save Style */
                    elementContent.html(value);
                    setMarginorPadding(elementContent, 'element', 'margin');
                    setMarginorPadding(elementContent, 'element', 'padding');

                    /** Save Linked */
                    elementContent.attr('data-margin-linked', elementLinked.margin);
                    elementContent.attr('data-padding-linked', elementLinked.padding);

                    /** Clean Script */
                    setTimeout(cleanSettingScript, 500);
                },
                cancel: function () {
                    /** Clean Script */
                    setTimeout(cleanSettingScript, 500);
                },
            }
        });
    });

    /** Initiate color picker */
    function initColorPicker(options = {}){
        let defaults = {
            el: '.color-picker',
            theme: 'classic',
            components: {
                /** Main Components */
                preview: true,
                opacity: true,
                hue: true,
                /** Input Output Options */
                interaction: {
                    hex: true,
                    rgba: true,
                    hsla: true,
                    hsva: true,
                    cmyk: true,
                    input: true,
                    clear: true,
                    save: true
                }
            }
        };
        return Pickr.create({...options, ...defaults});
    }

    /**
     * Set margin or padding from the setting
     * @var     string  dom     DOM element
     * @var     string  type    (row, element)
     * @var     string  type    (margin, padding)
     * */
    function setMarginorPadding(dom, type, MarginorPadding){
        $(dom).css(`${MarginorPadding}-top`, ($(`#${type}-${MarginorPadding}-top`).val()) ? $(`#${type}-${MarginorPadding}-top`).val() : '0' );
        $(dom).css(`${MarginorPadding}-right`, ($(`#${type}-${MarginorPadding}-right`).val()) ? $(`#${type}-${MarginorPadding}-right`).val() : '0' );
        $(dom).css(`${MarginorPadding}-bottom`, ($(`#${type}-${MarginorPadding}-bottom`).val()) ? $(`#${type}-${MarginorPadding}-bottom`).val() : '0' );
        $(dom).css(`${MarginorPadding}-left`, ($(`#${type}-${MarginorPadding}-left`).val()) ? $(`#${type}-${MarginorPadding}-left`).val() : '0' );
    }

    /**
     * Toggle Margin Linked
     * @var     string  type    (row, element)
     * @var     string  type    (margin, padding)
     * */
    function toggleMarginorPaddingLinked(type, MarginorPadding){
        /** Set Value */
        if(type=='row') rowLinked[MarginorPadding] = !rowLinked[MarginorPadding];
        if(type=='element') elementLinked[MarginorPadding] = !elementLinked[MarginorPadding];
        /** Change Icon */
        let icon = (type=='row') ? rowLinked : elementLinked;
            icon = (icon[MarginorPadding]) ? `<i class="fas fa-link"></i>` : `<i class="fas fa-unlink"></i>`;
        $(`#${type}-${MarginorPadding}-linked`).html(icon);
    }

    /**
     * Get Margin or Padding of row or element
     * @var     object  dom     row, element object
     * @var     string  type    (margin, padding)
     * */
    function getMarginorPadding(dom, MarginorPadding){
        return {
            'top' : $(dom).css(`${MarginorPadding}-top`),
            'right' : $(dom).css(`${MarginorPadding}-right`),
            'bottom' : $(dom).css(`${MarginorPadding}-bottom`),
            'left' : $(dom).css(`${MarginorPadding}-left`),
        };
    }

    /**
     * Set Margin or Padding of row or element
     * @var     string  type    (margin, padding)
     * @var     string  value   Value
     * */
    function setMarginorPaddingValue(type, MarginorPadding, value){
        $(`#${type}-${MarginorPadding}-top`).val(value.top);
        $(`#${type}-${MarginorPadding}-right`).val(value.right);
        $(`#${type}-${MarginorPadding}-bottom`).val(value.bottom);
        $(`#${type}-${MarginorPadding}-left`).val(value.left);
    }

    /**
     * Set Margin or Padding Linked Value
     * @var     string  type    (row, element)
     * @var     string  type    (margin, padding)
     * @var     string  value   Value
     * */
    function setMarginorPaddingLinkedValue(type, MarginorPadding, value){
        let linked = (type=='row') ? rowLinked : elementLinked;
        if(linked[MarginorPadding]){
            $(`#${type}-${MarginorPadding}-top`).val(value);
            $(`#${type}-${MarginorPadding}-right`).val(value);
            $(`#${type}-${MarginorPadding}-bottom`).val(value);
            $(`#${type}-${MarginorPadding}-left`).val(value);
        }
    }

    /**
     * Clean setting script, used to re-initiate setting modal dialog
     * */
    function cleanSettingScript(){
        /** Remove Setting */
        $('#builder_dom').find('.control-setting').remove();
        /** Clean TinyMCE */
        $('.mce-toolbar-grp').remove();
        /** Refresh Grid */
        initSortable();
        /** Render Grid */
        renderGrid();
        /** Reset MarginPadding Linked */
        rowLinked = { margin: true, padding: true };
        elementLinked = { margin: true, padding: true };
    }

    /** Trigger On Submit */
    $(document).on('submit', '#post', triggerOnSubmit);
    function triggerOnSubmit(){
        cleanSettingScript();
        return true;
    }

    /**
     * Grab #builder_dom into #template_html to be saved
     * */
    function renderGrid(){
        /** Copy Dom */
        let dom = $('#builder_dom').html();
        $('#rendered_dom').html(dom);
        /** Clean Dom */
        dom = $('#rendered_dom');
        $('.row, .row-content', dom).removeClass('ui-sortable ui-sortable-handle');
        $('.element, .element-content', dom).removeClass('ui-sortable ui-sortable-handle');
        /** Render */
        $('#template_html').val(dom.html());
    }

});
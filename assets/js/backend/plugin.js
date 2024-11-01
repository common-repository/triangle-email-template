/**
 * Handle section tab
 * @about-page
 * */
jQuery(document).on('click', '.triangle-container ul.nav-tab-general li', handleSectionTab);
jQuery(document).on('click', '.triangle-container ul.nav-tab-jconfirm li', handleSectionTab);
function handleSectionTab(){
    /** Animate */
    let animation = `animated ${window.trianglePlugin.options.animation_tab}`;
    animate(this, animation);
    /** Show Content */
    let parent = jQuery(this).parent();
    let tab_id = jQuery(this).attr('data-tab');
    jQuery('li', parent).removeClass('nav-tab-active');
    parent = jQuery(this).parent().parent().next();
    jQuery('.tab-content', parent).removeClass('current');
    jQuery(this).addClass('nav-tab-active');
    jQuery("#"+tab_id).addClass(`current animated ${window.trianglePlugin.options.animation_content}`);
}

/**
 * Animate UI Component
 * @params     object      Selector object that would like to be animated
 * @params     string      Type of animation in a form of string class 'animated bounce'
 * */
function animate(selector, animation){
    jQuery(selector).addClass(animation);
    jQuery(selector).on('animationend', () => { jQuery(selector).removeClass(animation); });
    return jQuery(selector);
}

/**
 * Convert given string into camel case
 * @params     string      String to be camelize
 * */
function camelize(text, separator="_") {
    return text.split(separator)
        .map(w => w.replace(/./, m => m.toUpperCase()))
        .join();
}

/** Additional function for wpautop */
function _autop_newline_preservation_helper (matches) {
    return matches[0].replace( "\n", "<WPPreserveNewline />" );
}

/** Javascript version of wpautop */
function wpautop(pee, br) {
    if(typeof(br) === 'undefined') {
        br = true;
    }
    var pre_tags = {};
    if ( pee.trim() === '' ) {
        return '';
    }
    pee = pee + "\n"; // just to make things a little easier, pad the end
    if ( pee.indexOf( '<pre' ) > -1 ) {
        var pee_parts = pee.split( '</pre>' );
        var last_pee = pee_parts.pop();
        pee = '';
        pee_parts.forEach(function(pee_part, index) {
            var start = pee_part.indexOf( '<pre' );

            // Malformed html?
            if ( start === -1 ) {
                pee += pee_part;
                return;
            }

            var name = "<pre wp-pre-tag-" + index + "></pre>";
            pre_tags[name] = pee_part.substr( start ) + '</pre>';
            pee += pee_part.substr( 0, start ) + name;

        });

        pee += last_pee;
    }
    pee = pee.replace(/<br \/>\s*<br \/>/, "\n\n");
    // Space things out a little
    var allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
    pee = pee.replace( new RegExp('(<' + allblocks + '[^>]*>)', 'gmi'), "\n$1");
    pee = pee.replace( new RegExp('(</' + allblocks + '>)', 'gmi'), "$1\n\n");
    pee = pee.replace( /\r\n|\r/, "\n" ); // cross-platform newlines
    if ( pee.indexOf( '<option' ) > -1 ) {
        // no P/BR around option
        pee = pee.replace( /\s*<option'/gmi, '<option');
        pee = pee.replace( /<\/option>\s*/gmi, '</option>');
    }
    if ( pee.indexOf('</object>') > -1 ) {
        // no P/BR around param and embed
        pee = pee.replace( /(<object[^>]*>)\s*/gmi, '$1');
        pee = pee.replace( /\s*<\/object>/gmi, '</object>' );
        pee = pee.replace( /\s*(<\/?(?:param|embed)[^>]*>)\s*/gmi, '$1');
    }
    if ( pee.indexOf('<source') > -1 || pee.indexOf('<track') > -1 ) {
        // no P/BR around source and track
        pee = pee.replace( /([<\[](?:audio|video)[^>\]]*[>\]])\s*/gmi, '$1');
        pee = pee.replace( /\s*([<\[]\/(?:audio|video)[>\]])/gmi, '$1');
        pee = pee.replace( /\s*(<(?:source|track)[^>]*>)\s*/gmi, '$1');
    }
    pee = pee.replace(/\n\n+/gmi, "\n\n"); // take care of duplicates
    // make paragraphs, including one at the end
    var pees = pee.split(/\n\s*\n/);
    pee = '';
    pees.forEach(function(tinkle) {
        pee += '<p>' + tinkle.replace( /^\s+|\s+$/g, '' ) + "</p>\n";
    });
    pee = pee.replace(/<p>\s*<\/p>/gmi, ''); // under certain strange conditions it could create a P of entirely whitespace
    pee = pee.replace(/<p>([^<]+)<\/(div|address|form)>/gmi, "<p>$1</p></$2>");
    pee = pee.replace(new RegExp('<p>\s*(</?' + allblocks + '[^>]*>)\s*</p>', 'gmi'), "$1", pee); // don't pee all over a tag
    pee = pee.replace(/<p>(<li.+?)<\/p>/gmi, "$1"); // problem with nested lists
    pee = pee.replace(/<p><blockquote([^>]*)>/gmi, "<blockquote$1><p>");
    pee = pee.replace(/<\/blockquote><\/p>/gmi, '</p></blockquote>');
    pee = pee.replace(new RegExp('<p>\s*(</?' + allblocks + '[^>]*>)', 'gmi'), "$1");
    pee = pee.replace(new RegExp('(</?' + allblocks + '[^>]*>)\s*</p>', 'gmi'), "$1");
    if ( br ) {
        pee = pee.replace(/<(script|style)(?:.|\n)*?<\/\\1>/gmi, _autop_newline_preservation_helper); // /s modifier from php PCRE regexp replaced with (?:.|\n)
        pee = pee.replace(/(<br \/>)?\s*\n/gmi, "<br />\n"); // optionally make line breaks
        pee = pee.replace( '<WPPreserveNewline />', "\n" );
    }
    pee = pee.replace(new RegExp('(</?' + allblocks + '[^>]*>)\s*<br />', 'gmi'), "$1");
    pee = pee.replace(/<br \/>(\s*<\/?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)/gmi, '$1');
    pee = pee.replace(/\n<\/p>$/gmi, '</p>');
    if ( Object.keys(pre_tags).length ) {
        pee = pee.replace( new RegExp( Object.keys( pre_tags ).join( '|' ), "gi" ), function (matched) {
            return pre_tags[matched];
        });
    }
    return pee;
}

/**
 * Check wether given string is valid
 * @params     string      String to be check
 * */
function isEmail(email){
    let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

/**
 * Validate form before submission
 * @params     object      Required form specs before it can be submitted
 * @params     object      Form object array containing values which will be validated
 * */
function validate_form(specs, states){
    let validation = { status: true, message: '' };
    /** Setup Message function */
    const setupMessage = (fieldName, extras = '') => {
        let message = fieldName.split('_');
            message.splice(0,1);
            message = camelize( message.join('_') ).replace(',',' ');
        return message + extras;
    }
    /** Validation process */
    specs.required.some((spec) => {
        /** Locate Element */
        let element = { position: -1, value: '' };
        states.some((state, index) => {
            if(state.name==spec){
                element.position = index;
                element.value = state.value;
                return true; }
        });
        /** Validate required fields */
        if(element.position==-1 || !element.value){
            validation.status = false;
            validation.message = (specs.messages && specs.messages[spec]) ? specs.messages[spec] :
                setupMessage(spec, ' field is required!');
            return true;
        }
        /** Validate fields type */
        else if (specs.types && specs.types[spec]) {
            if (specs.types[spec] == 'email' && !isEmail(element.value)) {
                validation.status = false;
                validation.message = (specs.messages && specs.messages[spec]) ? specs.messages[spec] :
                    setupMessage(spec, ` field is not valid! Please input valid email address!`);
            } else if (specs.types[spec] == 'integer' && !Number.isInteger(specs.types[spec])) {
                validation.status = false;
                validation.message = (specs.messages && specs.messages[spec]) ? specs.messages[spec] :
                    setupMessage(spec, ` field is not valid! Please input valid integer number!`);
            }
            if (!validation.status) return true;
        }
    });
    return validation;
}
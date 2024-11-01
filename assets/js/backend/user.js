    /**
     * Add Contact Option
     * @userPage
     * @return  void
     * */
    jQuery(document).ready(function( $ ) {
        $('#the-list tr').each(function(){
            let id = $(this).attr('id').replace('user-','');
            let url = `admin.php?page=triangle&user_id=${id}`;
            let html = `<span class="contact"> | <a href="${url}" class="triangle-contact">Contact</a></span>`;
            $('.row-actions').append(html);
        });
    });
<div
    id="<?= (isset($args['id']))? $args['id'] : '' ?>"
    class="triangle-loading"
>
    <img src="<?= unserialize(TRIANGLE_PATH)['plugin_url'] ?>/assets/img/loading.gif" class="ico-loading" alt="Loading..."><br>
    <span>Loading...</span>
</div>
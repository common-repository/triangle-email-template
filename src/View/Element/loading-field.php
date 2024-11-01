<div
    id="<?= (isset($args['id']))? $args['id'] : '' ?>"
    class="triangle-loading-field <?= (isset($args['class']))? $args['class'] : '' ?>"
>
    <div class="row">
        <div>
            <img src="<?= unserialize(TRIANGLE_PATH)['plugin_url'] ?>/assets/img/loading-field.gif" class="ico-loading" alt="Loading...">
        </div>
        <div class="col-1 loading-label">
            Loading...
        </div>
    </div>
</div>
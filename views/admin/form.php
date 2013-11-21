<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('blog:create_title') ?></h4>
    <?php else: ?>
        <h4><?php echo sprintf(lang('blog:edit_title'), $post->title) ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">

        <fieldset>
            <ul>
                <?php
                foreach ($stream_fields as $field):
                    echo $this->load->view('admin/partials/streams/form_single_display', array('field' => $field), true);
                endforeach;
                ?>
            </ul>
        </fieldset>

    </div>
</section>
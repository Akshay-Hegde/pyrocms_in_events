<ul>
    <?php foreach ($events as $event): ?>
        <li>
            <a href="{{ url:site }}inevents/view/<?php echo $event->id ?>">
                <span class="title_event_widget"><?php echo $event->title ?></span>
                <span class="date_event_widget"><?php echo $event->date ?></span>
                <img src="<?php echo $event->image ?>" height="292" width="241" alt="">
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<ul class="list-unstyled container">
	<?php

	foreach ($users as $user):?>
        <li class="row justify-content-between border">
            <div class="col-sm bg-light">
                <strong><?= $user->getName() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $user->getRole() ?></strong>
            </div>
        </li>
	<?php endforeach; ?>
</ul>
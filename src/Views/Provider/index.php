<ul class="list-unstyled container">
	<?php

	foreach ($providers as $provider):?>
        <li class="row justify-content-between border">
            <div class="col-sm bg-light">
                <strong><?= $provider->getName() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $provider->getAddress() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $provider->getCountry() ?></strong>
            </div>
        </li>
	<?php endforeach; ?>
</ul>
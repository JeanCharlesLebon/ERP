<ul class="list-unstyled container">
	<?php

	foreach ($clients as $client):?>
        <li class="row justify-content-between border">
            <div class="col-sm bg-light">
                <strong><?= $client->getName() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $client->getAddress() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $client->getCountry() ?></strong>
            </div>
        </li>
	<?php endforeach; ?>
</ul>
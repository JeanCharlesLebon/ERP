<ul class="list-unstyled container">
	<?php
	foreach ($companies as $company):?>
        <li class="row justify-content-between border">
            <div class="col-sm bg-light">
                <strong><?= $company->getName() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $company->getBalance() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $company->getCountry() ?></strong>
            </div>
        </li>
	<?php endforeach; ?>
</ul>
<ul class="list-unstyled container">
	<?php

	foreach ($transactions as $transaction):?>
        <li class="row justify-content-between border">
            <div class="col-sm bg-light">
                <strong><?= $transaction->getClientId() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $transaction->getCompanyId() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $transaction->getProviderId() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $transaction->getProductId() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $transaction->getProductQuantity() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $transaction->getResponsibleEmployeeId() ?></strong>
            </div>
        </li>
	<?php endforeach; ?>
</ul>
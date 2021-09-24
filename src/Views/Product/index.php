<ul class="list-unstyled container">
	<?php

	foreach ($products as $product):?>
        <li class="row justify-content-between border">
            <div class="col-sm bg-light">
                <strong><?= $product->getName() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $product->getStock() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $product->getPrice() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $product->getTax() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $product->getCompanyId() ?></strong>
            </div>
        </li>
	<?php endforeach; ?>
</ul>
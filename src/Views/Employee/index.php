<ul class="list-unstyled container">
	<?php

	foreach ($employees as $employee):?>
        <li class="row justify-content-between border">
            <div class="col-sm bg-light">
                <strong><?= $employee->getName() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $employee->getCountry() ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $employee->getBirthday()->format('Y-m-d') ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $employee->getFirstDayInCompany()->format('Y-m-d') ?></strong>
            </div>
            <div class="col-sm bg-light">
                <strong><?= $employee->getCompanyId() ?></strong>
            </div>
        </li>
	<?php endforeach; ?>
</ul>
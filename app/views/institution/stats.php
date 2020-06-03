<?php return function ($obj) {
    $teamTitle = $data = NULL; ?>
    <div class="row">
        <?php foreach ($obj->charts as $teamTitle => $data): ?>
            <div class="col-sm-6 col-lg-4 mb-3">
                <div class="card bg-light">
                    <div class="card-header"><?= $teamTitle ?></div>
                    <div class="card-body px-0 py-2">
                        <canvas class="chart" data-json='<?= json_encode($data, JSON_HEX_APOS) ?>'></canvas>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
    <?php
};
<?php return function ($obj) { ?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3 card-specialist">
        <div class="card">
            <div class="card-header">
                Tecnico/Specialista <?= $obj->index + 1 ?>
                <button type="button" class="close delete" aria-label="Remove" onclick="btnCloseDelete(this)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body">
                <?php if (isset($obj->user)): ?>
                    <input hidden type="number" id="id[<?= $obj->index ?>]" name="id[<?= $obj->index ?>]"
                           value="<?= $obj->user->id ?>">
                <?php endif ?>
                <?php view('user/register-fields-user', (array) $obj) ?>
            </div>
        </div>
    </div>
    <?php
};
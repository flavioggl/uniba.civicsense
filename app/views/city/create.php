<?php return function ($obj) { ?>
    <form class="row" method="post">
        <div class="col-sm">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dati Titolare</h5>
                    <?php view('user/register-fields-user', isset($obj->city) ? ["user" => $obj->city] : []) ?>
                </div>
            </div>
        </div>
        <div class="col-sm mt-3 mt-sm-0">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dati Città/Comune</h5>
                    <div class="form-group">
                        <label for="city_name">Nome</label>
                        <input type="text" class="form-control" id="city_name" name="city_name" placeholder="Bari"
                               maxlength="45" value="<?= isset($obj->city) ? $obj->city->city_name : NULL ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cap">CAP</label>
                        <input type="text" class="form-control" id="cap" name="cap" maxlength="6" pattern="[0-9]+"
                               value="<?= isset($obj->city) ? $obj->city->cap : 10000 ?>" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary"><?= isset($obj->city) ? "Aggiorna" : "Crea" ?></button>
                </div>
            </div>
        </div>
    </form>
    <?php
};
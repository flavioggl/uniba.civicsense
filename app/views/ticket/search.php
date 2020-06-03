<?php return function ($object) { ?>
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card card-home card-home-keyboard">
                <div class="card-img-top"></div>
                <form method="post" class="card-body">
                    <div class="form-group">
                        <label for="code">Digita qui il Codice di Tracking per verificare lo stato di avanzamento della segnalazione.</label>
                        <input required name="code" id="code" type="text" maxlength="8" minlength="8"
                               class="form-control form-control-lg text-center" placeholder="XX12A6D0">
                    </div>
                    <button class="btn btn-info">CERCA</button>
                    <?php if (isset($object->error)): ?>
                        <hr>
                        <div class="alert alert-danger" role="alert">Codice non trovato!</div>
                    <?php endif ?>
                </form>
            </div>
        </div>
    </div>
    <?php
};
<?php return function ($object) {
    $trouble = NULL; ?>
    <div class="row justify-content-center text-center">
        <form method="post" class="col-md-10 col-lg-8 col-xl-7" enctype="multipart/form-data">
            <div class="form-group px-3">
                <label for="trouble_id">Tipologia di Problema</label>
                <select name="trouble_id" id="trouble_id" required class="form-control">
                    <?php foreach ($object->troubles as $trouble): ?>
                        <option value="<?= $trouble->id ?>"><?= $trouble->description ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <hr>
            <div class="form-group px-3">
                <label for="description">Descrizione</label>
                <textarea class="form-control" id="description" name="description" placeholder="Dettaglio segnalazione"
                          maxlength="128" required></textarea>
            </div>
            <hr>
            <div class="form-group px-3">
                <label>Priorità</label>
                <div class="row">
                    <div class="col-4">
                        <label class="text-success">
                            <input type="radio" name="priority" required
                                   value="<?= \App\Model\Ticket::PRIORITY_GREEN ?>">
                            Bassa
                        </label>
                    </div>
                    <div class="col-4">
                        <label class="text-warning">
                            <input type="radio" name="priority" required
                                   value="<?= \App\Model\Ticket::PRIORITY_YELLOW ?>">
                            Media
                        </label>
                    </div>
                    <div class="col-4">
                        <label class="text-danger">
                            <input type="radio" name="priority" required value="<?= \App\Model\Ticket::PRIORITY_RED ?>">
                            Alta
                        </label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group px-3">
                <label>Posizione</label>
                <div id="alertPosition" class="alert alert-warning alert-dismissible mb-2 fade show"
                     role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Consentire al browser l'accesso alla posizione per ottenere automaticamente le coordinate.</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td colspan="2">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#modalMap" data-location="40.8909432,16.8437567">
                                <i class="fas fa-map-marked-alt"></i>
                                Visualizza su Maps
                            </button>
                            <button type="button" class="btn btn-sm btn-dark ml-3" id="btnGPS">
                                <i class="fas fa-map-marker-alt"></i>
                                Posizione Attuale
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right pr-2"><label for="latitude">Latitudine</label></th>
                        <td>
                            <input id="latitude" name="latitude" type="number" step="0.0000001" value="40.8909432"
                                   required class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right pr-2"><label for="longitude">Longitudine</label></th>
                        <td>
                            <input id="longitude" name="longitude" type="number" step="0.0000001" value="16.8437567"
                                   required class="form-control">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="form-group px-3">
                <div class="custom-file">
                    <label class="custom-file-label" for="photo_src">Foto</label>
                    <input type="file" class="custom-file-input" id="photo_src" name="photo_src" required
                           accept="image/jpeg">
                </div>
            </div>
            <div class="form-group px-3">
                <div class="custom-file">
                    <label class="custom-file-label" for="video_src">Video (facoltativo)</label>
                    <input type="file" class="custom-file-input" id="video_src" name="video_src" accept="video/mp4">
                </div>
            </div>
            <button class="btn btn-primary">Invia</button>
        </form>
    </div>
    <?php
};
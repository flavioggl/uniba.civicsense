<?php return function ($object) {
    $trouble = NULL; /** @var $trouble \App\Model\Trouble */ ?>
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-info float-right" data-toggle="modal"
                            data-target="#modalTroubleCreate">
                        Nuovo
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <table class="card-body table table-hover">
                    <tbody>
                    <?php foreach ($object->troubles as $trouble): ?>
                        <tr>
                            <td><?= $trouble->description ?></td>
                            <td>
                                <a href="/trouble/<?= $trouble->id ?>/delete" class="btn btn-sm btn-danger">
                                    Elimina
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
};
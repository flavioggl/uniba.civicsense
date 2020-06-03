<?php return function ($object) {
    $contract = NULL; /** @var $contract \App\Model\Contract */ ?>
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-info float-right" data-toggle="modal"
                            data-target="#modalContractCreate">
                        Nuovo
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <table class="card-body table table-hover">
                    <thead>
                    <tr>
                        <th>Tipologia</th>
                        <th>Ente/Azienda</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($object->contracts as $contract): ?>
                        <tr>
                            <td>
                                <?= $contract->trouble()->description ?>
                            </td>
                            <td>
                                <?= $contract->institution()->name ?>
                            </td>
                            <td>
                                <form action="/contract/delete" method="post">
                                    <input type="number" name="institution_id" value="<?= $contract->institution_id ?>"
                                           hidden>
                                    <input type="number" name="trouble_id" value="<?= $contract->trouble_id ?>" hidden>
                                    <button class="btn btn-sm btn-danger">
                                        Elimina
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
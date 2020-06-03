<?php return function ($object) {
    $institution = NULL;
    /** @var $institution \App\Model\Institution */ ?>
    <div class="card">
        <div class="card-header">
            <a class="btn btn-info float-right" href="/institution/create">
                Nuovo
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <table class="card-body table table-hover">
            <thead>
            <tr>
                <th>Ragione Sociale</th>
                <th>Partita IVA</th>
                <th>Servizio</th>
                <th>Titolare</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($object->institutions as $institution):
                $manager = $institution->manager(); ?>
                <tr>
                    <td><?= $institution->name ?></td>
                    <td><?= $institution->vat ?></td>
                    <td><?= $institution->service_description ?></td>
                    <td><?= $manager->surname, ' ', $manager->name ?></td>
                    <td>
                        <div class="btn-group">
                            <i class="fas fa-ellipsis-v px-2" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false"></i>
                            <div class="dropdown-menu dropdown-menu-right text-right">
                                <a href="/institution/<?= $institution->id ?>/update" class="dropdown-item">
                                    Modifica
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/institution/<?= $institution->id ?>/delete" class="dropdown-item">
                                    Elimina
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php
};
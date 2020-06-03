<?php return function ($object) {
    /** @var \App\Model\Specialist[] $specialists */
    $specialists = isset($object->specialists) ? $object->specialists->toArray() : [NULL, NULL]; ?>
    <form method="post" class="row justify-content-center text-center">
        <div class="col-12 mb-3">
            <button id="team-add-btn" type="button" class="btn btn-info" data-next-index="<?= count($specialists) ?>">
                <i class="fas fa-plus"></i> Aggiungi Componente
            </button>
            <button type="submit" class="btn btn-primary ml-3">Aggiorna Team</button>
        </div>
        <?php
        foreach ($specialists as $i => $specialist) {
            view('specialist/register', ["index" => $i, "user" => $specialist]);
        }
        ?>
    </form>
    <?php
};
<?php return function ($object) {
    $startSpecialists = 2; ?>
    <form method="post" class="row justify-content-center text-center">
        <div class="col-12 mb-3">
            <button id="team-add-btn" type="button" class="btn btn-info" data-next-index="<?= $startSpecialists ?>">
                <i class="fas fa-plus"></i> Aggiungi Componente
            </button>
            <button type="submit" class="btn btn-primary ml-3">Crea Team</button>
        </div>
        <?php
        for ($i = 0; $i < $startSpecialists; $i++) {
            view('specialist/register', ["index" => $i]);
        }
        ?>
    </form>
    <?php
};
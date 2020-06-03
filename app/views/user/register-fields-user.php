<?php return function ($obj) {
    $i = isset($obj->index) ? "[" . $obj->index . "]" : NULL;
    /** @var \App\Model\User|null $user */
    $user = isset($obj->user) ? $obj->user : NULL; ?>
    <div class="form-group">
        <label for="email<?= $i ?>">Email</label>
        <input type="email" class="form-control" id="email<?= $i ?>" name="email<?= $i ?>" placeholder="user@domain.com"
               value="<?= $user ? $user->email : NULL ?>" required>
    </div>
    <div class="form-group">
        <label for="password<?= $i ?>">Password</label>
        <input type="password" class="form-control" id="password<?= $i ?>" name="password<?= $i ?>"
               placeholder="Password" min="8" required>
    </div>
    <div class="form-group">
        <label for="name<?= $i ?>">Nome</label>
        <input type="text" class="form-control" id="name<?= $i ?>" name="name<?= $i ?>" placeholder="Mario"
               maxlength="45" value="<?= $user ? $user->name : NULL ?>" required>
    </div>
    <div class="form-group">
        <label for="surname<?= $i ?>">Cognome</label>
        <input type="text" class="form-control" id="surname<?= $i ?>" name="surname<?= $i ?>" placeholder="Rossi"
               maxlength="45" value="<?= $user ? $user->surname : NULL ?>" required>
    </div>
    <?php
} ?>
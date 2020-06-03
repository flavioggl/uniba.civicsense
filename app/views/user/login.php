<?php return function ($object) { ?>
    <div class="row justify-content-center text-center">
        <div class="col-sm-10 col-md-8 col-lg-7 col-xl-6">
            <form method="post" autocomplete="on">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                           placeholder="user@domain.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                           min="8" required>
                </div>
                <button type="submit" class="btn btn-primary">Accedi</button>
            </form>
        </div>
    </div>
    <?php
};
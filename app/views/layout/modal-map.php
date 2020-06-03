<?php return function ($obj) { ?>
    <div class="modal fade" id="modalMap" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Posizione</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-0 py-0">
                    <iframe src='' width="100%" style="height: 75vh" frameborder='0' scrolling='no' marginheight='0'
                            marginwidth='0'></iframe>
                </div>
            </div>
        </div>
    </div>
    <?php
};
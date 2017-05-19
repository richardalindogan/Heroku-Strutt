<div class="modal fade" id="contentModal<?php echo $i; ?>" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div>
                    <div class="thumbnail"><img src="<?php echo $fetch['content_path']; ?>"></div>
                    <div><p><?php echo htmlspecialchars($fetch['description']); ?></p></div>
                </div>
            </div>
        </div>
    </div>
</div>
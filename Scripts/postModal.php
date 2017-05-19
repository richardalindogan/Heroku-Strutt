<?php
    $_SESSION['postToken']= bin2hex(openssl_random_pseudo_bytes(36));
?>
<div class="modal fade" id="postModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button><br>
                <form action="Services/upload.php" method="post" enctype="multipart/form-data">
                    <div class="form-group center-block">
                        <input type="file" name="uPost" id="uPost" accept="image/jpeg,image/png"></input>
                        <select name="category">
                            <option value="fantasy">Fantasy</option>
                            <option value="sketches">Sketches</option>
                            <option value="concepts">Concepts</option>
                            <option value="casualwear">Casualwear</option>
                            <option value="cosplay">Cosplay</option>
                            <option value="streetwear">Streetwear</option>
                        </select><br>
                        <label>Description</label>
                        <textarea class="form-control" rows="5" name="postDescription" id="post-description" placeholder="100 words or less" maxlength="750"></textarea>
                        <input type="hidden" name="tid" value="<?php echo $_SESSION['postToken']; ?>" class="form-control" />
                    </div>
                    <button class="btn btn-inverse" type="submit">Post</button>
                </form>
            </div>
      </div>
    </div>
</div>
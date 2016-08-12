        <div class="row">
         <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="fa fa-user-plus"></i>
                        <span class="caption-subject bold uppercase"> <?php echo $title ?></span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <?php echo form_open('cabang/edit', 'role="form"'); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label id="nama-cabang">Nama Cabang</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-plus-square-o"></i>
                                </span>
                                <input id="id-cabang" type="hidden" class="form-control" value="<?php echo $loopEditCabang->id_cabang; ?>" name="id-cabang" placeholder="Nama Cabang">
                                <input id="nama-cabang" type="text" required class="form-control" value="<?php echo $loopEditCabang->nama; ?>" name="nama-cabang" placeholder="Nama Cabang">
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label id="nik">Target</label>
                            <?php echo form_error('target'); ?>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-plus-square-o"></i>
                                </span>
                                <input id="target" type="text" class="form-control" name="target" value="<?php echo $loopEditCabang->target; ?>" placeholder="target"> 
                                <span id='errmsg' style="color:red;"></span>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label id="nik">PIC</label>
                            <?php echo form_error('pic'); ?>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-plus-square-o"></i>
                                </span>
                                <input id="pic" type="text" class="form-control" name="pic" value="<?php echo $loopEditCabang->pic; ?>" placeholder="target"> 
                                <span id='errmsg' style="color:red;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="submit" class="btn blue"></input>
                        <a type="button" class="btn default" href="/cabang">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
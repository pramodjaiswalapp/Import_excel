<!--aside close--><!--right Panel-->
<div class="right-panel">
    <div class="inner-right-panel">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper white-wrapper">
            <section class="content-header">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Dashboard</a></li>            
                    <li class="active">Excel Import</li>
                </ol>
            </section>
            <!-- Content section -->
            <section class="content">
                <div class="box box-info">

                    <div class="box-header with-border">
                        <h3 class="box-title"> Demo Data Excel Import</h3>
                    </div><!-- /.box-header -->

                    <p style="text-align:center"><strong>Import Sample Excel File. </strong> </p>
                    <?php
                    if ($this->session->flashdata('message') != '') {

                        echo $this->session->flashdata('message');
                    }
                    ?>
                    <!-- form start -->
                    <form method="post" action="" onsubmit="return excelValidation();" class="form-horizontal" enctype="multipart/form-data">
                        <div class="login-inner">

                            <div class="form-group">
                                <label for="excelname" class="col-sm-2 control-label">Select Demo Data Excel <span style="color:red;">*</span></label>
                                <div class="col-sm-5">
                                    <input type="file" style="display:block;"name="excelname" id="excelname" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">

                                    <span style="color:red;" id="excelname_err"> </span>
                                </div>

                                <div class="col-sm-3">
                                    <a href="<?php echo base_url() . 'public/doc/demo_file.xlsx'; ?>" download> Download Sample excelsheet</a>  
                                </div>

                                <div class="box-footer" style="text-align:center;">							
                                    <button type="submit" class="btn btn-info" name="login" value="LogIn">Import File</button>
                                </div>
                            </div>
                    </form>
                </div><!-- /.box -->
            </section>
            <!-- modal close-->
        </div><!-- /.content-wrapper -->

        <!-- modal close-->
    </div><!-- /.content-wrapper -->

    <script>
        function excelValidation() {
            if ($('#excelname').val().trim() == '') {
                $('#excelname_err').html('Please select a excel file.');
                $('#excelname').focus();
                return false;
            } else if ($('#excelname').val().trim() != '') {
                var ext = $('#excelname').val().substring($('#excelname').val().lastIndexOf('.') + 1);
                if (ext != 'xlsx') {
                    $('#excelname_err').html('Please select a excel file only!');
                    $('#excelname').focus();
                    return false;
                }
            }
        }

    </script>




</div>
</div>

</div>
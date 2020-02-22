<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
  <div class="pcoded-wrapper">
    <div class="pcoded-content">
      <div class="pcoded-inner-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <div class="page-header-title">
                  <h5 class="m-b-10">Sửa câu hỏi</h5>
                </div>
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#!">Sửa câu hỏi</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <div class="main-body">
          <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <form method="POST" role="form" id="edit_question_form">
              <div class="row">
                <div class="col-md-12 col-xl-12">
                  <div class="card checked">
                    <div class="card-header">
                      <h5>Tùy chọn</h5>
                    </div>
                    <div class="card-body">
                      <a href="quan-ly-ngan-hang-cau-hoi"><button type="button" class="btn shadow-2 btn-primary"><i class="fas fa-file-alt"></i>Quản lý câu hỏi</button></a>
                      <div class="input-group cust-file-button mb-3">
                        <div class="input-group-prepend">
                          <button class="btn btn-primary" type="button">Thêm ảnh</button>
                        </div>
                        <div class="custom-file">
                          <input type="file" id="file" onchange="upload_image(this)" class="custom-file-input">
                          <label class="custom-file-label" for="file">Chọn ảnh JPG hoặc PNG</label>
                        </div>
                      </div>
                      <div class="form-group">
                          <label>Đường dẫn ảnh</label>
                          <input id="url" type="text" class="form-control" value="" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-xl-12">
                  <div class="card checked">
                    <div class="card-header">
                      <h5>Câu hỏi</h5>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <div id="toolbar"></div>
                        <textarea class="form-control" id="question_detail" name="question_detail" rows="3" required><?=$question->question_content?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-xl-12">

                </div>
                <div class="col-md-6 col-xl-6">
                  <div class="card checked">
                    <div class="card-header">
                      <h5>Đáp Án A</h5>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <textarea contenteditable="true" class="form-control" id="answer_a" name="answer_a" rows="3" required><?=$question->answer_a?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-6">
                  <div class="card checked">
                    <div class="card-header">
                      <h5>Đáp Án B</h5>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <textarea contenteditable="true" class="form-control" id="answer_b" name="answer_b" rows="3" required><?=$question->answer_b?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-6">
                  <div class="card checked">
                    <div class="card-header">
                      <h5>Đáp Án C</h5>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <textarea contenteditable="true" class="form-control" id="answer_c" name="answer_c" rows="3" required><?=$question->answer_c?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-6">
                  <div class="card checked">
                    <div class="card-header">
                      <h5>Đáp Án D</h5>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <textarea contenteditable="true" class="form-control" id="answer_d" name="answer_d" rows="3" required><?=$question->answer_d?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-xl-12">
                  <div class="card checked">
                    <div class="card-header">
                      <h5>Tùy chỉnh</h5>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-12 col-md-6 col-xl-4">
                          <div class="form-group">
                            <label for="correct_answer">Đáp án đúng</label>
                            <select class="form-control" name="correct_answer" id="correct_answer">
                              <?php
                                if($question->correct_answer == $question->answer_a) {
                                    $a = "selected";
                                }
                                if($question->correct_answer == $question->answer_b) {
                                    $b = "selected";
                                }
                                if($question->correct_answer == $question->answer_c) {
                                    $c = "selected";
                                }
                                if($question->correct_answer == $question->answer_d) {
                                    $d = "selected";
                                }
                              ?>
                              <option value="A" <?=$a?>>A</option>
                              <option value="B" <?=$b?>>B</option>
                              <option value="C" <?=$c?>>C</option>
                              <option value="D" <?=$d?>>D</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-4">
                          <div class="form-group">
                            <label for="grade_id">Khối</label>
                            <select class="form-control" name="grade_id" id="grade_id">
                              <?php
                                for ($i = 0; $i < count($grades);$i++){
                                    if($grades[$i]->grade_id == $question->grade_id){
                                        echo '<option value="'.$grades[$i]->grade_id.'" selected>'.$grades[$i]->detail.'</option>';
                                    } else {
                                        echo '<option value="'.$grades[$i]->grade_id.'">'.$grades[$i]->detail.'</option>';
                                    }
                                }
                             ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-4">
                          <div class="form-group">
                            <label for="subject_id">Môn Học</label>
                            <select class="form-control" name="subject_id" id="subject_id">
                              <?php
                                for ($i = 0; $i < count($subjects);$i++){
                                    if($subjects[$i]->subject_id == $question->subject_id){
                                        echo '<option value="'.$subjects[$i]->subject_id.'" selected>'.$subjects[$i]->subject_detail.'</option>';
                                    } else {
                                        echo '<option value="'.$subjects[$i]->subject_id.'">'.$subjects[$i]->subject_detail.'</option>';
                                    }
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-4">
                          <div class="form-group">
                            <label>Chương</label>
                            <input value="<?=$question->unit?>" type="number" class="form-control" name="unit" required></input>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-4">
                          <div class="form-group">
                            <label for="level_id">Độ khó</label>
                            <select class="form-control" name="level_id" id="level_id">
                              <?php
                              if($question->level_id == 1) {
                                  $ez = "selected";
                              }
                              if($question->level_id == 2) {
                                  $nor = "selected";
                              }
                              if($question->level_id == 3) {
                                  $dif = "selected";
                              }
                              if($question->level_id == 4) {
                                  $sdif = "selected";
                              }
                              ?>
                              <option value="1" <?=$ez?>>Nhận biết</option>
                              <option value="2" <?=$nor?>>Thông hiểu</option>
                              <option value="3" <?=$dif?>>Vận dụng</option>
                              <option value="4" <?=$sdif?>>Vận dụng cao</option>
                            </select>
                            <input name="question_id" type="hidden" value="<?=$question->question_id?>">
                          </div>
                        </div>
                      </div>
                      <button id="submit_question" type="button" class="btn btn-primary"><i class="feather icon-check-circle"></i>Sửa câu hỏi</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <!-- [ Main Content ] end -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Required Js -->
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<!-- datatable Js -->
<script src="assets/plugins/data-tables/js/datatables.min.js"></script>
<!-- jquery-validation Js -->
<script src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<!-- notification Js -->
<script src="assets/plugins/notification/js/bootstrap-growl.min.js"></script>
<!-- custom js -->
<script src="res/js/edit_question.js"></script>
<!-- sweet alert Js -->
<script src="assets/plugins/sweetalert/js/sweetalert.min.js"></script>

<script>
    CKEDITOR.config.toolbar_Full =
        [
        { name: 'document', items : [ 'Source'] },
        { name: 'editing', items : [ 'Find'] },
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline'] },
        { name: 'paragraph', items : [ 'JustifyLeft','JustifyCenter','JustifyRight'] },
        ];
    CKEDITOR.config.toolbar_GX =
        [
        { name: 'wiris', items: ['ckeditor_wiris_formulaEditor', 'ckeditor_wiris_formulaEditorChemistry']},
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat' ] },
        { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
        { name: 'document', items: [ 'Source', '-', , 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-'] },
        { name: 'insert', items: [ 'Image','Table', 'Smiley', 'SpecialChar' ] },
        ];

    let ckEditorC = {
        toolbar:'GX',
        height: '150px',
        extraPlugins: 'divarea,ckeditor_wiris'
    };
    CKEDITOR.config.height = '40px';
    CKEDITOR.plugins.addExternal('divarea', '../extraplugins/divarea/', 'plugin.js');
    CKEDITOR.plugins.addExternal('sharedspace', '../extraplugins/sharedspace/', 'plugin.js');
    CKEDITOR.config.removePlugins = 'maximize';
    CKEDITOR.config.removePlugins = 'resize';
    CKEDITOR.config.sharedSpaces = { top: 'toolbar'};
    CKEDITOR.replace('question_detail',{
        toolbar:'GX',
        height: '300px',
        extraPlugins: 'divarea,ckeditor_wiris'
    });
    CKEDITOR.replace('answer_a',ckEditorC);
    CKEDITOR.replace('answer_b',ckEditorC);
    CKEDITOR.replace('answer_c',ckEditorC);
    CKEDITOR.replace('answer_d',ckEditorC);
    MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
</script>

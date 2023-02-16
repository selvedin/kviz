<script>
  const DELETE_TITLE = '<?= __('Are You sure') ?>';
  const DELETE_QUESTION = '<?= __('You wont be able to revert this') ?>';
  const CANCEL_TEXT = '<?= __('Cancel') ?>';
  const CONFIRM_TEXT = '<?= __('Yes') ?>';
  const SUCCESS_TEXT = '<?= __('Completed') ?>';


  const IMAGE_FILE_TYPES = ["jpg", "jpeg", "png", "gif"];
  const WORD_FILE_TYPES = ["doc", "docx"];
  const EXCEL_FILE_TYPES = ["xls", "xlsx"];
  const PPT_FILE_TYPES = ["ppt", "pptx"];
  const PDF_FILE_TYPES = ["pdf"];
  const ARCHIVE_FILE_TYPES = ["zip", "rar", "tar", "tar.gz"];


  const SWAL_DELETE_OPTIONS = {
    title: DELETE_TITLE,
    text: DELETE_QUESTION + "!",
    icon: 'warning',
    showCancelButton: true,
    cancelButtonText: CANCEL_TEXT,
    confirmButtonText: CONFIRM_TEXT,
    customClass: {
      confirmButton: 'btn btn-danger me-3',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  };

  function resolveIcon(ext) {
    const ex = ext.toLowerCase();
    if (IMAGE_FILE_TYPES.includes(ex)) return "file-image";
    if (WORD_FILE_TYPES.includes(ex)) return "file-word";
    if (EXCEL_FILE_TYPES.includes(ex)) return "file-excel";
    if (PPT_FILE_TYPES.includes(ex)) return "file-powerpoint";
    if (PDF_FILE_TYPES.includes(ex)) return "file-pdf";
    if (ARCHIVE_FILE_TYPES.includes(ex)) return "file-archive";
    return "file";
  }
</script>
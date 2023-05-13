<?php

use yii\helpers\Url;

?>
<script>
  //VUE APP
  const mainApp = new Vue({
    el: '#ocrApp',
    data: {
      title: '<?= __('Ocr') ?>',
      content: ''
    },
    mounted() {},
    methods: {
      getContent: function(filename) {
        const self = this;
        $.get(`<?= Url::to(['gpt/get-file-content']) ?>?filename=${filename}`, function(data) {
          self.content = data;
        });
      },
    },
    computed: {},
    watch: {}
  });
</script>
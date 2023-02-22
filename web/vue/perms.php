<?php

use app\models\Perms;
use app\models\Roles;

$model = Perms::getAllData();

?>
<script>
  const mainApp = new Vue({
    el: '#mainApp',
    data: {
      permisssions: <?= json_encode($model) ?>,
      roles: <?= json_encode(Roles::getNames()) ?>,
      levels: <?= json_encode(PERMISSION_LEVELS) ?>,
      permObject: null,
      role: null,
      permission: 0,
      rolePermissions: []
    },
    watch: {
      permObject: function(val) {
        this.getPermissions();
      },
      role: function(val) {
        this.getPermissions();
        this.getRolePermissions();
      },
    },
    mounted: function() {},
    computed: {
      jsonTemplate: function() {
        const self = this;
      },
    },
    methods: {
      savePermissions: function() {
        const self = this;
        $.post("<?= Yii::$app->urlManager->createUrl('perms/save') ?>", {
          permObject: self.permObject,
          role: self.role,
          permission: self.permission
        }, (data) => {
          if (data.error) {
            errorNotification(data.message);
            return;
          }
          successNotification();
        });
      },
      getPermissions: function() {
        const self = this;
        if (self.permObject && self.role) {
          $.get(`<?= Yii::$app->urlManager->createUrl('perms/get') ?>?object=${self.permObject}&role=${self.role}`, (result) => {
            if (result) {}
            self.permission = +result;
            $('#permissionsLevel').val(self.permission).trigger('change');
          })
        }
      },
      getRolePermissions: function() {
        const self = this;
        self.rolePermissions = [];
        if (self.role) {
          $.get(`<?= Yii::$app->urlManager->createUrl('perms/get-role-permissions') ?>?role=${self.role}`, (result) => {
            if (result) {}
            self.rolePermissions = result;
          })
        }
      }
    }
  });
</script>
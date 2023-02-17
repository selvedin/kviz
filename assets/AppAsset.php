<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'vendor/fonts/fontawesome.css',
        'vendor/fonts/tabler-icons.css',
        'vendor/fonts/flag-icons.css',
        // 'vendor/css/rtl/core.css',
        // 'vendor/css/rtl/theme-default.css',
        'vendor/libs/perfect-scrollbar/perfect-scrollbar.css',
        'vendor/libs/node-waves/node-waves.css',
        'vendor/libs/typeahead-js/typeahead.css',
        'vendor/libs/apex-charts/apex-charts.css',
        'vendor/libs/swiper/swiper.css',
        'vendor/libs/datatables-bs5/datatables.bootstrap5.css',
        'vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
        'vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css',
        'vendor/libs/sweetalert2/sweetalert2.css',
        'vendor/libs/toastr/toastr.css',
        'vendor/css/pages/cards-advance.css',
        'css/demo.css',
        // 'css/custom.css',
    ];
    public $js = [
        'vendor/libs/popper/popper.js',
        'vendor/js/bootstrap.js',
        'vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
        'vendor/libs/node-waves/node-waves.js',

        'vendor/libs/hammer/hammer.js',
        'vendor/libs/i18n/i18n.js',
        'vendor/libs/typeahead-js/typeahead.js',

        'vendor/js/menu.js',
        'vendor/js/cards-actions.js',
        'vendor/libs/apex-charts/apexcharts.js',
        'vendor/libs/swiper/swiper.js',
        'vendor/libs/datatables/jquery.dataTables.js',
        'vendor/libs/datatables-bs5/datatables-bootstrap5.js',
        'vendor/libs/datatables-responsive/datatables.responsive.js',
        'vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js',
        'vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js',
        'vendor/libs/jquery-sticky/jquery-sticky.js',
        'vendor/libs/sweetalert2/sweetalert2.js',
        'vendor/libs/toastr/toastr.js',
        'js/main.js',
        'js/custom.js',

        // 'js/dashboards-analytics.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap5\BootstrapAsset'
    ];
}

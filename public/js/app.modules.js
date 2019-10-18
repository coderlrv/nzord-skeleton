
//  Definir modulos de dependencia JS e CSS
//  Ex: nomeModulo : array de dependencias
//  'datapicker':[
//     '/path/picker.js',
//     '/path/picker.css'  
//  ]
//  ----------------------------------------------
//  Utilizar no codigo o modulo: Ex
//  require(['datapicker'],function(){
//      seu codigo aqui...
//  })
//  ----------------------------------------------
//  OU com varios modulos
//  require(['jquery','datapicker'],function(){
//      seu codigo aqui...
//  })

var modules = {
    'adminlte':[
        baseUrl+'/assets/adminlte/dist/css/AdminLTE.min.css',
        baseUrl+'/assets/adminlte/dist/js/adminlte.min.js'
    ],
    'nzord':[
        baseUrl+'/assets/nzord-app/src/css/nzord.css',
        baseUrl+'/assets/nzord-app/src/js/nzord.js',
        baseUrl+'/assets/nzord-app/src/js/nzord-validation.js'
    ],
    'datatableJS':[
        baseUrl+'/assets/coder-lrv--ndatatables/datatables.min.css',
        baseUrl+'/assets/coder-lrv--ndatatables/datatables.min.js',
    ],
    'contextMenu':[
        baseUrl+'/assets/jquery-contextmenu/dist/jquery.contextMenu.min.js',
        baseUrl+'/assets/jquery-contextmenu/dist/jquery.ui.position.min.js',
        baseUrl+'/assets/jquery-contextmenu/dist/jquery.contextMenu.min.css'
    ],
    'datetimepicker':[
        baseUrl+'/assets/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
        baseUrl+'/assets/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
    ],
    'codemirror':[
        baseUrl+'/assets/codemirror/lib/codemirror.css',
        baseUrl+'/assets/codemirror/theme/monokai.css',
        baseUrl+'/assets/codemirror/lib/codemirror.js'
    ],
    'codemirrorPlugins':[
    ],
    'summernote':[ 
        baseUrl+'/assets/summernote/dist/summernote.css',
        baseUrl+'/assets/summernote/dist/summernote.min.js',
        baseUrl+'/assets/adminlte/dist/css/AdminLTE.min.css'
    ],
    'mask': [
        baseUrl+'/assets/jquery.inputmask/dist/inputmask/jquery.inputmask.js'
    ],
    'maskMoney':[
        baseUrl+'/assets/jquery-maskmoney/dist/jquery.maskMoney.min.js'
    ],
    'select2':[
        baseUrl+'/assets/select2/dist/js/select2.min.js',
        baseUrl+'/assets/select2/dist/css/select2.min.css'
    ],
    'colorpicker':[
        baseUrl+'/assets/colorpicker/jquery.colorpicker.js',
        baseUrl+'/assets/colorpicker/jquery.colorpicker.css'
    ],
    'chosen':[
        baseUrl+'/assets/chosen/chosen.jquery.min.js?v=$.now()',
        baseUrl+'/assets/bootstrap-chosen/bootstrap-chosen.css',
        baseUrl+'/assets/adminlte/dist/css/AdminLTE.min.css'
    ],
    'highcharts':[
        baseUrl+'/assets/highcharts/highcharts.js',
    ],
    'highchartModules':[
        baseUrl+'/assets/highcharts/modules/no-data-to-display.js',
        baseUrl+'/assets/highcharts/modules/exporting.js'
    ],
    'fullCalendar':[
        baseUrl+'/assets/fullcalendar/dist/fullcalendar.min.css',
        baseUrl+'/assets/fullcalendar/dist/fullcalendar.min.js'
    ],
    'fullCalendarLocale':[
        baseUrl+'/assets/fullcalendar/dist/locale-all.js'
    ],
    'slimScroll':[
        baseUrl+'/assets/jquery-slimscroll/jquery.slimscroll.min.js'
    ],
    'wysihtml5':[
        baseUrl+'/assets/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.min.css',
        baseUrl+'/assets/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all.min.js'
    ],
    'base64':[
        baseUrl+'/assets/jquery-base64/jquery.base64.min.js'
    ]   
};
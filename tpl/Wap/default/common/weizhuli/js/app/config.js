require.config({
	baseUrl: '',
	paths: {
		'css': 'tpl/Wap/default/common/weizhuli/js/lib/css.min',
		'jquery': 'tpl/Wap/default/common/weizhuli/js/lib/jquery-1.11.1.min',
		'jquery.hammer': 'tpl/Wap/default/common/weizhuli/js/lib/jquery.hammer-full.min',
		'angular': 'tpl/Wap/default/common/weizhuli/js/lib/angular.min',
		'bootstrap': 'tpl/Wap/default/common/weizhuli/js/lib/bootstrap.min',
		'underscore': 'tpl/Wap/default/common/weizhuli/js/lib/underscore-min',
		'iscroll': 'tpl/Wap/default/common/weizhuli/js/lib/iscroll-lite',
		'filestyle': 'tpl/Wap/default/common/weizhuli/js/lib/bootstrap-filestyle.min',
		'daterangepicker': '../../components/daterangepicker/daterangepicker',
		'WeixinApi': 'tpl/Wap/default/common/weizhuli/js/lib/WeixinApi'
	},
	shim:{
		'jquery.hammer': {
			exports: "$",
			deps: ['jquery']
		},
		'angular': {
			exports: 'angular',
			deps: ['jquery']
		},
		'bootstrap': {
			exports: "$",
			deps: ['jquery']
		},
		'iscroll': {
			exports: "IScroll",
		},
		'filestyle': {
			exports: '$',
			deps: ['bootstrap']
		},
		'daterangepicker': {
			exports: '$',
			deps: ['bootstrap', 'moment', 'css!../../components/daterangepicker/daterangepicker.css']
		}
	},
});
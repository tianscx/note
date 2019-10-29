// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import Mint from 'mint-ui'
import { Toast,MessageBox } from 'mint-ui'
import 'mint-ui/lib/style.css'
import store from './store'
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import 'font-awesome/css/font-awesome.min.css'
import {
  faUser, faChartLine, faAmericanSignLanguageInterpreting,faUserCircle,faBars,faPlus,faBookmark,faSortDown,faCheck,faTimes,faStar,faCheckSquare,faSquare,faTrash,faAngleLeft,faPencilAlt,faEdit
} from '@fortawesome/free-solid-svg-icons';
import axios from 'axios';
import $ from './util.js';
import './appback.js';

import VueHtml5Editor from 'vue-html5-editor'
Vue.use(VueHtml5Editor,{
    // 全局组件名称，使用new VueHtml5Editor(options)时该选项无效
    // global component name
    name: "vue-html5-editor",
    // 是否显示模块名称，开启的话会在工具栏的图标后台直接显示名称
    // if set true,will append module name to toolbar after icon
    showModuleName: false,
    // 自定义各个图标的class，默认使用的是font-awesome提供的图标
    // custom icon class of built-in modules,default using font-awesome
    // icons: {
    //     text: "fa fa-pencil",
    //     color: "fa fa-paint-brush",
    //     font: "fa fa-font",
    //     align: "fa fa-align-justify",
    //     list: "fa fa-list",
    //     link: "fa fa-chain",
    //     unlink: "fa fa-chain-broken",
    //     tabulation: "fa fa-table",
    //     image: "fa fa-file-image-o",
    //     hr: "fa fa-minus",
    //     eraser: "fa fa-eraser",
    //     undo: "fa-undo fa",
    //     "full-screen": "fa fa-arrows-alt",
    //     info: "fa fa-info",
    // },
    // 语言，内建的有英文（en-us）和中文（zh-cn）
    //default en-us, en-us and zh-cn are built-in
    language: "zh-cn",
    // 自定义语言
    i18n: {
        //specify your language here
        "zh-cn": {
            "align": "对齐方式",
            "image": "图片",
            "list": "列表",
            "link": "链接",
            "unlink": "去除链接",
            "table": "表格",
            "font": "文字",
            "full screen": "全屏",
            "text": "排版",
            "eraser": "格式清除",
            "info": "关于",
            "color": "颜色",
            "please enter a url": "请输入地址",
            "create link": "创建链接",
            "bold": "加粗",
            "italic": "倾斜",
            "underline": "下划线",
            "strike through": "删除线",
            "subscript": "上标",
            "superscript": "下标",
            "heading": "标题",
            "font name": "字体",
            "font size": "文字大小",
            "left justify": "左对齐",
            "center justify": "居中",
            "right justify": "右对齐",
            "ordered list": "有序列表",
            "unordered list": "无序列表",
            "fore color": "前景色",
            "background color": "背景色",
            "row count": "行数",
            "column count": "列数",
            "save": "确定",
            "upload": "上传",
            "progress": "进度",
            "unknown": "未知",
            "please wait": "请稍等",
            "error": "错误",
            "abort": "中断",
            "reset": "重置"
        }
    },
    // 隐藏不想要显示出来的模块
    // the modules you don't want
    hiddenModules: [],
    // 自定义要显示的模块，并控制顺序
    // keep only the modules you want and customize the order.
    // can be used with hiddenModules together
    visibleModules: [
        "text",
        "color",
        "font",
        "align",
        "list",
        // "link",
        // "unlink",
        // "tabulation",
        // "image",
        "hr",
        "eraser",
        "undo",
        // "full-screen",
        // "info",
    ],
    // 扩展模块，具体可以参考examples或查看源码
    // extended modules
    modules: {
        //omit,reference to source code of build-in modules
    }
});

Vue.prototype.$axios = axios;
Vue.prototype.$ = $;
import qs from 'qs';
Vue.prototype.qs = qs.stringify;

library.add(
  faUser,
  faChartLine,
  faAmericanSignLanguageInterpreting,
  faUserCircle,
  faBars,
  faPlus,
  faBookmark,
  faSortDown,
  faCheck,
  faTimes,
  faStar,
  faCheckSquare,
  faSquare,
  faTrash,
    faAngleLeft,
    faPencilAlt,
    faEdit
);

Vue.$toast = Vue.prototype.$toast = Toast;
Vue.$messageBox = Vue.prototype.$messageBox = MessageBox;

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.use(Mint)

Vue.config.productionTip = false

/* eslint-disable no-new */

Vue.filter('formatDate',function(value, type){
    value = Number(value)
  let date = new Date(value);
  let y = date.getFullYear();
  let MM = date.getMonth() + 1;
  MM = MM < 10 ? ('0' + MM) : MM;
  let d = date.getDate();
  d = d < 10 ? ('0' + d) : d;
  let h = date.getHours();
  h = h < 10 ? ('0' + h) : h;
  let m = date.getMinutes();
  m = m < 10 ? ('0' + m) : m;
  let s = date.getSeconds();
  s = s < 10 ? ('0' + s) : s;
  if(type){
    return y + '-' + MM + '-' + d + ' ' + h + ':' + m + ':' + s;
  }else {
    return y + '-' + MM + '-' + d ;
  }
})
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})


// formatDate: function (value, type) {
//   let date = new Date(value);
//   let y = date.getFullYear();
//   let MM = date.getMonth() + 1;
//   MM = MM < 10 ? ('0' + MM) : MM;
//   let d = date.getDate();
//   d = d < 10 ? ('0' + d) : d;
//   let h = date.getHours();
//   h = h < 10 ? ('0' + h) : h;
//   let m = date.getMinutes();
//   m = m < 10 ? ('0' + m) : m;
//   let s = date.getSeconds();
//   s = s < 10 ? ('0' + s) : s;
//   if(type){
//     return y + '-' + MM + '-' + d + ' ' + h + ':' + m + ':' + s;
//   }else {
//     return y + '-' + MM + '-' + d ;
//   }
// },

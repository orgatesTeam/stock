/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 60);
/******/ })
/************************************************************************/
/******/ ({

/***/ 60:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(61);


/***/ }),

/***/ 61:
/***/ (function(module, exports) {

webSocketIp = webSocketIP();
var conn = new WebSocket(webSocketIp);
conn.onopen = function (e) {
    window.vm.firstSend();
    console.log("Connection established!");
};

conn.onmessage = function (e) {
    data = jQuery.parseJSON(e.data);

    if (data["users"] != undefined) {
        window.vm.users = data["users"];
        return;
    }

    if (data["alert"] != undefined) {
        alert(data["alert"]);
        return;
    }

    if (data["message"] != undefined) {
        window.vm.chatItems = [];
        chatItems = data["message"];
        chatItems.forEach(function (chatItem) {
            window.vm.chatItems.push(jQuery.parseJSON(chatItem));
        });

        messageBox = $("#messageBox");
        messageBox.animate({ scrollTop: 999999999 }, 200);
        return;
    }
};

window.vm = new Vue({
    el: '#app',
    data: {
        chatItems: [],
        message: '',
        users: [],
        showUser: true
    },
    methods: {
        send: function send() {
            if (this.message == '') {
                return;
            }
            console.log(this.message);
            chatItem = {};
            chatItem['content'] = this.message;
            chatItem['userID'] = loginUser();

            conn.send(JSON.stringify(chatItem));
            this.message = '';
        },
        firstSend: function firstSend() {
            //第一次驗證身份
            chatItem = {};
            chatItem['firstLogin'] = '';
            chatItem['userID'] = loginUser();
            conn.send(JSON.stringify(chatItem));
        },
        facebookLink: function facebookLink(id) {
            return 'https://facebook.com/' + id;
        },
        facebookUserImg: function facebookUserImg(id) {
            return 'https://graph.facebook.com/v2.10/' + id + '/picture?type=normal';
        },
        isSelf: function isSelf(chatItem) {
            return chatItem.userID == loginUser();
        },
        changeShowUser: function changeShowUser() {
            this.showUser = !this.showUser;
        }
    },
    computed: {
        onlineCount: function onlineCount() {
            return _.keys(this.users).length;
        }
    }
});

document.querySelector('#app').addEventListener('keypress', function (e) {
    var key = e.which || e.keyCode;
    if (key === 13) {
        // 13 is enter
        window.vm.send();
    }
});

/***/ })

/******/ });
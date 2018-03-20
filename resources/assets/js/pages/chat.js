require('../bootstrap');

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
            window.vm.chatItems.push(
                jQuery.parseJSON(chatItem)
            )
        })

        messageBox = $("#messageBox");
        messageBox.animate({scrollTop: 999999999}, 200);
        return;
    }
};

window.vm = new Vue({
    el: '#app',
    data: {
        chatItems: [],
        message: '',
        users: [],
        showUser: true,
    },
    methods: {
        send: function () {
            if (this.message == '') {
                return;
            }
            console.log(this.message);
            chatItem = {};
            chatItem['content'] = (this.message);
            chatItem['userID'] = (loginUser());

            conn.send(JSON.stringify(chatItem));
            this.message = '';
        },
        firstSend: function () {
            //第一次驗證身份
            chatItem = {};
            chatItem['firstLogin'] = '';
            chatItem['userID'] = (loginUser());
            conn.send(JSON.stringify(chatItem));
        },
        facebookLink: function (id) {
            return 'https://facebook.com/' + id;
        },
        facebookUserImg: function (id) {
            return 'https://graph.facebook.com/v2.10/' + id + '/picture?type=normal';
        },
        isSelf: function (chatItem) {
            return chatItem.userID == loginUser();
        },
        changeShowUser: function () {
            this.showUser = !this.showUser;
        }
    },
    computed: {
        onlineCount: function () {
            return _.keys(this.users).length
        }
    }
});

document.querySelector('#app').addEventListener('keypress', function (e) {
    var key = e.which || e.keyCode;
    if (key === 13) { // 13 is enter
        window.vm.send();
    }
});

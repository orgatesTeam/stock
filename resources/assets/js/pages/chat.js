require('../bootstrap');

webSocketIp = webSocketIP();
var conn = new WebSocket(webSocketIp);
conn.onopen = function (e) {
    console.log("Connection established!");
};

conn.onmessage = function (e) {
    data = jQuery.parseJSON(e.data);
    if (data["users"] != undefined) {
        window.vm.users = data["users"];
        return;
    }
    window.vm.chatItems = [];
    chatItems = jQuery.parseJSON(e.data);
    chatItems.forEach(function (chatItem) {
        window.vm.chatItems.push(
            jQuery.parseJSON(chatItem)
        )
    })
};

window.vm = new Vue({
    el: '#app',
    data: {
        chatItems: [],
        message: '',
        users: [],
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

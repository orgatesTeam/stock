require('../bootstrap');

webSocketIp = webSocketIP();
var conn = new WebSocket(webSocketIp);
conn.onopen = function (e) {
    console.log("Connection established!");
};

conn.onmessage = function (e) {
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
    },
    methods: {
        send: function () {
            console.log(4);
            if (this.message == '') {
                return;
            }
            console.log(this.message);
            chatItem = {};
            chatItem['content'] = (this.message);
            chatItem['user'] = (loginUser());

            conn.send(JSON.stringify(chatItem));
            this.message = '';
        }
    }
});

document.querySelector('#app').addEventListener('keypress', function (e) {
    var key = e.which || e.keyCode;
    if (key === 13) { // 13 is enter
        window.vm.send();
    }
});

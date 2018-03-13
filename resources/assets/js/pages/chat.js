require('../bootstrap');

var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function (e) {
    console.log("Connection established!");
};

conn.onmessage = function (e) {
    data = jQuery.parseJSON(e.data);
    console.log(data);
    window.vm.messages.push(data);
};

window.vm = new Vue({
    el: '#app',
    data: {
        chatContent: '',
        messages: [],
    },
    methods: {
        send: function () {
            console.log(1);
            if (this.chatContent == '') {
                return;
            }
            conn.send(this.chatContent);
            this.chatContent = '';
        }
    }
});



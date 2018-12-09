var execSync = require('child_process').execSync;
var cmd = "php artisan  chat_server:serve";

var options = {
    encoding: 'utf8'
};

console.log(execSync(cmd, options));
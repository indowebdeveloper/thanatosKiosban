var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');

server.listen(9090);
var users = [];
io.on('connection', function (socket) {
    // Logic ini untuk doowhop
    var data = socket.handshake.query;
    var uid = data.uid;
    var state = typeof users[uid] === 'object'
    if(!state){
      users[uid]={
        'sockets':[]
      }
    }
    users[uid].sockets.push(socket.id);
    // End doowhop

    var redisClient = redis.createClient({
      "host":"127.0.0.1",
      "port":"6379"
    });

    redisClient.subscribe(['notification']);

    redisClient.on('message', function(channel,message) {
        socket.emit(channel,message);
    });

    socket.on('disconnect', function() {
        // Untuk doowhop juga
        users[uid].sockets.pop(socket.id);
        if(users[uid].sockets.length < 1){
          console.log("USER DISCONNECTED")
        }
        // End
    });
    console.log(users);
});

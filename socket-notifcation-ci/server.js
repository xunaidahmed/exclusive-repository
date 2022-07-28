var socket 	= require( 'socket.io' );
var express = require('express');

var app 	= express();
var server 	= require('http').createServer(app);
var io 		= socket.listen( server );

var port 	= process.env.PORT || 3000;

io.on('connection', function (socket) {
    socket.on('socketNotify', (message) => socket.broadcast.emit('myBroadCastNotify', message));
});

//server.listen(port);
server.listen(port, () => console.log('Server listening at port %d', port) );
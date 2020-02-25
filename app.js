const fs = require('fs');
const http = require('http');
const https = require('https');
const express = require('express');
var mysql      	= require('mysql');
const app = express();
var io    = require('socket.io');

var clientConnect     = 0;
var connectionsArray 	= [];
var POLLING_INTERVAL 	= 5000;
var last_ticket   = "";

// Certificate
//const privateKey = fs.readFileSync('/etc/letsencrypt/live/io.therubymedia.com/privkey.pem', 'utf8');
//const certificate = fs.readFileSync('/etc/letsencrypt/live/io.therubymedia.com/cert.pem', 'utf8');
//const ca = fs.readFileSync('/etc/letsencrypt/live/io.therubymedia.com/chain.pem', 'utf8');

//const credentials = {
//	key: privateKey,
//	cert: certificate,
//	ca: ca
//};

app.use((req, res) => {
	res.send('Hello there !');
});

// Starting both http & https servers
const httpServer = http.createServer(app);
//const httpsServer = https.createServer(credentials, app);

httpServer.listen(810, () => {
	console.log('HTTP Server running on port 810');
});

//httpsServer.listen(4431, () => {
//	console.log('HTTPS Server running on port 4431');
//});


var connection 	= mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'anaksapi85',
  database : 'db_cch'
});
var sio = io.listen(httpServer);

var setOnlineStatus = function(userid){
  var sql	= 'update sys_user set is_online = 1  where id = "'+userid+'"';
  console.log(sql);
	connection.query(sql, function(err, results) {
		sio.sockets.emit('onlineStatus', JSON.stringify(results));
	});
}

var setOfflineStatus = function(userid){	
	var sql	= 'update sys_user set is_online = 0  where id = "'+userid+'"';
	connection.query(sql, function(err, results) {
		sio.sockets.emit('onlineStatus', JSON.stringify(results));
	});
}

var streamNewTicket = function(s, kpos){

  var sql = 'select id, tujuan_pengaduan, jenis_layanan, awb, no_ticket from ticket where info_aduan = "PENGADUAN" order by auto_id desc limit 1';
  connection.query(sql, function(err, result){
    console.log(result);
    if(result.length > 0){
      var tujuan = result[0].tujuan_pengaduan.split(",");
    
      var i;
      for (i = 0; i < tujuan.length; i++) {
        
        var text_notif = "";
        text_notif += "Tiket Baru No : "+result[0].no_ticket+"\n";
        text_notif += "Layanan : "+result[0].jenis_layanan+"\n";
        text_notif += "No Barcode / AWB : "+result[0].awb+"\n";
        var json = {'tid':result[0].id, 'message':text_notif,'kantor_pos':tujuan[i],'type':'new'};
        console.log(json);
        if( last_ticket != "" && last_ticket != result[0].id ){
          sio.sockets.emit('newTicket',json);
        }
        last_ticket = result[0].id;
      }

      
    }
    
    if (connectionsArray.length){
      const timer = setTimeout(streamNewTicket, POLLING_INTERVAL);
    }
  });

  
} 

var returnData = function(uid) {
	//sql = "SELECT * FROM device_log WHERE id > 0 ORDER BY id DESC LIMIT 1 ";
	//var query = db.query(sql),resultData = [];
	if(uid != undefined){
		myid	= uid;
	}
	
	var sql = "select * from ticket where complaint_origin = " + myid;
	
	connection.query(sql, function(err, results) {
		//var resultData = [];
		//var totalTicket= 0;
		//results.forEach(function(myid) {
		//	resultData.push({day:dateFormat(myid.date, "yyyy-mm-dd"), v:myid.total});
		//	totalTicket = totalTicket + myid.total;
		//});
		//if (connectionsArray.length){
			  //pollingTimer = setTimeout(returnData, POLLING_INTERVAL);
			  //updateSockets({ resultData: resultData, totalTicket: totalTicket})
		//}
	});
}

sio.sockets.on('connection', function(socket){
  clientConnect++
  //console.log(socket.id);
  sio.sockets.emit('user_online',clientConnect);
  
  var client 	= socket.handshake.query.kantor_pos.split("-");
  var uid     = client[0];
  var kpos    = client[1];
  //console.log(client);
  //console.log("User Online " + clientConnect);
  socket.join(kpos);

  
  
  setOnlineStatus(uid);
  if (!connectionsArray.length) {
      console.log('HELLOW' + uid);
      

      streamNewTicket(socket, kpos);
      //streamResponseTicket(kpos);
      //streamRequestCloseTicket(kpos);
  }

  connectionsArray.push(socket);
  //console.log(connectionsArray);
  socket.on('disconnect', function() {
      // Decrease the socket count on a disconnect, emit
      clientConnect--
      setOfflineStatus(uid);
      sio.sockets.emit('user_online', clientConnect)
  
      var socketIndex = connectionsArray.indexOf(socket);
      //console.log('socket = ' + socketIndex + ' disconnected');
      if (socketIndex >= 0) {
        connectionsArray.splice(socketIndex, 1);
      }
  })


/*
  socket.on('new note', function(data){
      // New note added, push to all sockets and insert into db
      notes.push(data)
      io.sockets.emit('new note', data)
      // Use node's db injection format to filter incoming data
      db.query('INSERT INTO notes (note) VALUES (?)', data.note)
  })
*/
  /*
  // Check to see if initial query/notes are set
  if (! isInitNotes) {
      // Initial app start, run db query
      connection.query('SELECT * FROM ticket order by id desc limit 1')
          .on('result', function(data){
              // Push results onto the notes array
              notes.push(data)
          })
          .on('end', function(){
              // Only emit notes after query has been completed
              socket.emit('initial notes', notes)
          })

      isInitNotes = true
  } else {
      // Initial notes already exist, send out
      socket.emit('initial notes', notes)
  }
  */
});


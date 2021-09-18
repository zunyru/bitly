const express = require('express')
const fs = require('fs')
const bodyParser = require('body-parser')
const path = require('path')
const fileuploader = require('fileuploader')

var app = express();

app.use(bodyParser.urlencoded({ extended: false }))
app.use(bodyParser.json())

// set static paths
app.use(express.static(__dirname + '/public'));
app.get(['/dist/*', '/examples/*', '/uploads/*'],function(a,b){var c=__dirname+a.path.replace(/\//g, '\\').replace(/^\\dist/, '\\..\\..\\..\\dist').replace(/^\\examples/, '\\..\\..\\..\\examples').replace(/^\\uploads/, '\\uploads');fs.existsSync(c)?b.sendFile(path.resolve(c)):(b.statusCode=404,b.write('404 not found'),b.end())});

// routing
app.get('/', function(req, res) {
    res.sendFile(__dirname + '/views/index.html');
});

app.post('/ajax_upload', function(req, res) {
    
    // initialize fileuploader
    var uploader = fileuploader('files', {
        title: 'auto',
        uploadDir: 'uploads/'
    }, req, res);
    
    // call to upload the files
    uploader.upload(function(data) {
        
        // if uploaded and success
        if (data.isSuccess) {
            
        }
            
        res.end(JSON.stringify(data));
    });
    
});

app.post('/ajax_remove', function(req, res) {
    if (req.body && typeof req.body.file == 'string') {
        var file = 'uploads/' + req.body.file.replace(/[\/\\]/g, '');
	
        if(fs.existsSync(file))
            fs.unlink(file, function(err) {});
    }
});

// init server
app.listen(8000);
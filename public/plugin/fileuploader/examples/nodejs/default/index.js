const express = require('express')
const fs = require('fs')
const path = require('path')
const fileuploader = require('fileuploader')

var app = express();

// set static paths
app.use(express.static(__dirname + '/public'));
app.get(['/dist/*', '/examples/*', '/uploads/*'],function(a,b){var c=__dirname+a.path.replace(/\//g, '\\').replace(/^\\dist/, '\\..\\..\\..\\dist').replace(/^\\examples/, '\\..\\..\\..\\examples').replace(/^\\uploads/, '\\uploads');fs.existsSync(c)?b.sendFile(path.resolve(c)):(b.statusCode=404,b.write('404 not found'),b.end())});

// routing
app.get('/', function(req, res) {
    res.sendFile(__dirname + '/views/index.html');
});

app.post('/upload', function(req, res) {
    
    var uploadDir = 'uploads/',
        preloadedFiles = [];
    
    // preload the files
    fs.readdirSync(uploadDir).forEach(function(value, index) {
        var file = uploadDir + value,
            stats = fs.statSync(file);

        if (stats.isFile()) {
            preloadedFiles.push({
                name: value,
                type: null,
                size: stats.size,
                file: file,
                relative_path: file
            });
        }
    });
    
    // initialize fileuploader
    var uploader = fileuploader('files', {
        title: 'name',
        uploadDir: uploadDir,
        files: preloadedFiles
    }, req, res);
    
    // call to process req.body and to upload the files
    uploader.upload(function(data) {
        var response = '';
        
        // unlink the removed preloaded files
        uploader.getRemovedFiles().forEach(function(item, index) {
            fs.unlink(item.relative_path || item.file, function(err) {}); 
        });
        
        // if uploaded and success
        if (data.isSuccess)
            response = uploader.getFileList();
        
        // if warnings
        if (data.hasWarnings)
            response = data.warnings.map(warn => warn.message).join('<br>');
            
        res.end(JSON.stringify(response, null, 4));
    });
    
});

// init server
app.listen(8000);
/**
 * Fileuploader
 * Copyright (c) 2019 Innostudio.de
 * Website: https://innostudio.de/fileuploader/
 * Version: 2.2 (23-Nov-2019)
 * Requires: multer, mime-types and gm
 * License: https://innostudio.de/fileuploader/documentation/#license
 */
const multer = require('multer')
const mime = require('mime-types')
const fs  = require('fs')
const gm = require('gm')
const emptyFn = function() {}

const error_messages = {
    EMPTY_FIELD: 'No file was choosed. Please select one',
    MAX_FILES_NUMBER: 'Maximum number of files is exceeded',
    INVALID_TYPE: 'File type is not allowed for {file_name}',
    MAX_SIZE: 'Files are too large',
    MAX_FILE_SIZE: '{file_name} is too large'
}

var Fileuploader = function(fieldname, options, req, res) {
    var defaults = {
        limit: null,
        maxSize: null,
        fileMaxSize: null,
        extensions: null,
		disallowedExtensions: null,
        required: false,
        uploadDir: 'uploads/',
        title: ['auto', 12],
        replace: false,
        editor: null,
        listInput: true,
        files: [],
        move_uploaded_file: function(file) {
            fs.renameSync(file.tmp, file.file);

            return true;
        },
        validate_file: null
    };
    
    this.req = req;
    this.res = res;
    this.options = extendDefaults.call(this, options, defaults);
    this.multer = multer({
        dest: this.options.uploadDir,
        fileFilter: fileFilter
    });
    this.field = {
        name: fieldname,
        input: [],
        listInput: null
    };
    
    return this;
}

Fileuploader.prototype.getFileList = function(customKey) {
    var result = [],
        files = this.options.files;

    if (isset(customKey)) {
        files.forEach(function(item, index) {
            var attr = getFileAttribute(item, customKey);
            
            result.push(attr ? attr : item.file);
        });
    } else {
        result = files;
    }

    return result;
}

Fileuploader.prototype.getUploadedFiles = function() {
    return this.options.files.filter(item => isset(item.uploaded));
}

Fileuploader.prototype.getPreloadedFiles = function() {
    return this.options.files.filter(item => !isset(item.uploaded));
}
    
Fileuploader.prototype.getRemovedFiles = function(customKey = 'file') {
    var result = [],
        files = this.options.files,
        listInput = this.field.listInput;
    
    if (listInput != null) {
        files.forEach(function(item, index, object) {
            if (listInput.list.indexOf(getFileAttribute(item, customKey)) == -1 && !isset(item.uploaded)) {
                result.push(item);
                object.splice(index, 1);
            }
        });
    }
    
    return result;
}

Fileuploader.prototype.getListInput = function() {
    return this.field.listInput;
}

Fileuploader.prototype.generateInput = function() {
    var attributes = [],
        properties = Object.assign({}, this.options, {name: this.field.name});
		
    for(var key in properties) {
        var value = properties[key],
            attribute = 'data-fileuploader-' + key;
        
        if (value) {
            switch (key) {
                case 'limit':
                case 'maxSize':
                case 'fileMaxSize':
                    attributes.push({key: attribute, value: value});
                    break;
                case 'listInput':
                    attributes.push({key: attribute, value: typeof value == 'boolean' ? JSON.stringify(value) : value});
                    break;
                case 'extensions':
                    attributes.push({key: attribute, value: value.join(',')});
                    break;
                case 'name':
                    attributes.push({key: key, value: value});
                    break;
                case 'required':
                    attributes.push({key: key, value: ''});
                    break;
                case 'files':
                    attributes.push({key: attribute, value: JSON.stringify(value)});
                    break;
            }
        }
    }

    return '<input type="file" ' + attributes.map(attr => attr.key + "='" + attr.value.replace(/\'/g, '"') + "'").join(' ') + '>';
}

Fileuploader.prototype.resize = function(filename, width = null, height = null, destination = null, crop = false, quality = 90, rotation = 0, callback = null) {
    if (!fs.existsSync(filename) || /^image\//.test(this.mimeContentType(filename)) == false)
        return false;
    
    var source = gm(filename),
        imageWidth = 0,
        imageHeight = 0,
        hasRotation = rotation,
		hasCrop = crop instanceof Object || crop == true,
		hasResizing = width || height,
        destInfo = pathinfo(destination || filename),
        ratio;
    
    source.size(function(err, value) {
        if (!value)
            return;
        
        imageWidth = value.width;
        imageHeight = value.height;
        crop = {
            left: 0,
			top: 0,
			width: imageWidth,
			height: imageHeight,
			_paramCrop: crop
        };
        if (crop._paramCrop instanceof Object)
            Object.assign(crop, crop._paramCrop);
        
        source.autoOrient();
    
        if (hasRotation)
            source.rotate('black', rotation);

        width = width || crop.width;
        height = height || crop.height;
        ratio = width / height;
            
        if (hasCrop) {
            source.crop(crop.width, crop.height, crop.left, crop.top);
        }
        
        var imageRatio = imageWidth / imageHeight;

        if (crop._paramCrop === true) {
            if (imageRatio >= ratio) {
                crop.newWidth = crop.width / (crop.height / height);
                crop.newHeight = height;
            } else {
                crop.newHeight = crop.height / (crop.width / width);
                crop.newWidth = width;
            }

            crop.left = (crop.newWidth - width) / 2;
            crop.top = (crop.newHeight - height) / 2;
            
            if (crop.width < width || crop.height < height) {
                crop.left = crop.width < width ? width/2 - crop.width/2 : 0;
                crop.top = crop.height < height ? height/2 - crop.height/2 : 0;
                crop.newWidth = crop.width;
                crop.newHeight = crop.height;
            }

            source.resize(crop.newWidth, crop.newHeight, "!").crop(width, height, crop.left, crop.top);
        } else if (crop.width > width && crop.height > height) {
            var newRatio = crop.width / crop.height;

            if (ratio > newRatio)
                width = height * newRatio;
            else
                height = width / newRatio;

            source.resize(width, height, "!");
        }
            
        source.quality(quality);
        source.setFormat(destInfo.extension || 'png');
        source.write(destination || filename, callback || emptyFn);
    });
}
    
Fileuploader.prototype.mimeContentType = function(file) {
    return mime.lookup(file);
}

Fileuploader.prototype.cleanChunkedFiles = function(directory, time = 3600000) {
    fs.readdir(directory, function(err, files) {
        (files || []).filter(file => /^\.unconfirmed\_/.test(file)).forEach(function(file, index) {
            file = directory + file;

            fs.stat(file, function(err, stat) {
                if (err || stat.isDirectory())
                    return;

                var now = new Date().getTime(),
                    endTime = new Date(stat.ctime).getTime() + time;

                if (now > endTime)
                    return fs.unlink(file, emptyFn);
            });
        });
    });
}
    
Fileuploader.prototype.upload = function(callback) {
    var _ = this,
        type_ = _.options.limit === 1 ? 'single' : 'array',
        data = {
            hasWarnings: false,
            isSuccess: true,
            warnings: [],
            files: [],
            
            _callback: callback,
            _setStatus: function(status, warning, next) {
                var data = this,
                    callback = data._callback;
                
                if (status !== null) {
                    if (status === true) {
                        data.isSuccess = true;
                    } else {
                        data.isSuccess = false;
                        data.hasWarnings = true;
                        if (warning)
                            data.warnings.push(warning);
                    }
                }
                
                if (next && typeof callback == 'function') {
                    var isProcessing = false;
                    
                    _.options.files.forEach(function(item) {
                        if (isset(item._processing)) {
                            item._processingCallback = function() {
                                delete item._processing;
                                delete item._processingCallback;
                                
                                data._setStatus(null, null, true);
                            };
                            isProcessing = true;
                        }
                    });
                    
                    if (isProcessing)
                        return;
                    
                    delete data._callback;
                    delete data._setStatus;
                    
                    callback(data);
                }
            }
        };

    _.multer[type_](_.field.name)(_.req, _.res, function (err) {
        if (err)
            return data._setStatus(false, {code: err.code, message: err.message}, true);
        
        var files = type_ == 'single' ? [_.req.file] : _.req.files,
            fields = _.req.body,
            chunk = isset(fields) && isset(fields._chunkedd) && files.length == 1 && isJson(fields._chunkedd) ? JSON.parse(fields._chunkedd) : false,
            v = validate_files.call(_, files);
        
        _.field.input = files.length;
        _.field.listInput = getListInputFiles.call(_, _.field.name);
        
        if (v === true) {
            if (chunk && files.length > 0) {
                var file = files[0],
                    tmp = _.options.uploadDir + '.unconfirmed_';

                if (isset(chunk.isFirst))
                    tmp += chunk.temp_name = filterFilename(file.filename);
                else
                    tmp += filterFilename(chunk.temp_name);

                if (!isset(chunk.isFirst) && !fs.existsSync(tmp))
                    return;

                var w = fs.createWriteStream(tmp, {flags: 'a+'}),
                    r = fs.createReadStream(file.path);

                w.on('close', function() {
                    unlinkTmp(file);

                    if (isset(chunk.isLast)) {
                        file.path = tmp;
                        file.filename = chunk.temp_name;
                        file.originalname = chunk.name;
                        file.mimetype = chunk.type || _.mimeContentType(tmp);
                        file.size = chunk.size;

                        handleUpload.call(_, files, data);
                    } else {
                        _.res.end(JSON.stringify({
                            fileuploader: {
                                temp_name: chunk.temp_name
                            }
                        }));
                    }
                });
                
                return r.pipe(w);
            }

            handleUpload.call(_, files, data);
        } else {
            data._setStatus(false, {code: v, message: error_messages[v]}, true);
        }
        
        files.forEach(function(file, key) {
            unlinkTmp(file);
        });
    });
}

function handleUpload(files, data) {
    var _ = this,
        options = _.options,
        listInput = _.field.listInput;
    
    for(var i = 0; i<files.length; i++) {
        var file = {
            name: files[i].originalname,
            tmp: files[i].path,
            tmp_name: files[i].filename,
            type: files[i].mimetype,
            size: files[i].size
        };

        var listInputName = '0:/' + file.name,
            fileInList = listInput === null || listInput.list.indexOf(listInputName) > -1,
            nameInfo = pathinfo(file.name),
            d = new Date();

        file.oldname = file.name;
        file.oldtitle = nameInfo.title;
        file.extension = nameInfo.extension;
		file.format = file.type.substr(0, file.type.indexOf('/'));
        file.title = file.oldtitle;
        file.size2 = formatSize(file.size);
        file.name = generateFilename(options, file);
        file.title = pathinfo(file.name).title;
        file.file = options.uploadDir + file.name;
        file.replaced = fs.existsSync(_.options.uploadDir + file.name);
        file.date = d;

        var valid = validate_files.call(_, file);

        if (valid === true) {
           if (fileInList) {
               var fileListIndex = 0;

               if (listInput) {
                   fileListIndex = listInput.list.indexOf(listInputName);
                   file.listProps = listInput.values[fileListIndex];
                   listInput.list.splice(fileListIndex, 1);
                   listInput.values.splice(fileListIndex, 1);
               }

               data.files.push(file);
           }
        } else {
            if (!fileInList)
                continue;

            data.files = [];
            data._setStatus(false, {code: valid, message: parseVariables(error_messages[valid] || valid, file)});
            break;
        }
    }
    
    if (!data.hasWarnings) {
        data.files.forEach(function(file, key) {
            if (options.move_uploaded_file(file)) {
                file.uploaded = true;
                delete file.chunked;
                delete file.tmp;
                delete file.tmp_name;

                options.files.push(file);
            } else {
                data.files.splice(key, 1);
            }
        });
    }

    if (listInput)
        options.files.forEach(function(item, index) {
            if (!isset(item.listProps)) {
                var fileListIndex = listInput.list.indexOf(item.file)

                if (fileListIndex > -1)
                    item.listProps = listInput.values[fileListIndex];
            }

            if (isset(item.listProps)) {
                delete item.listProps.file;

                if (item.listProps.length == 0)
                    delete item.listProps;
            }
        });
    
    editFiles.call(_, data);
    sortFiles.call(_, data);

    return data._setStatus(null, null, true);
}

function validate_files(data) {
    if (data instanceof Array) {
        if (this.options.required && data.length + this.options.files.length == 0)
            return 'EMPTY_FIELD';
        if (this.options.limit && data.length + this.options.files.length > this.options.limit)
            return 'MAX_FILES_NUMBER';
        if (this.options.maxSize && (data.reduce((a, b) => a + b.size, 0)+this.options.files.reduce((a, b) => a + b.size, 0))/1000000 > this.options.maxSize)
            return 'MAX_SIZE';
    } else {
        if (this.options.extensions && (this.options.extensions.indexOf(data.extension) == -1 && !this.options.extensions.filter(function(val) { return val.indexOf(data.type) > -1 || val.indexOf(data.format + '/*') > -1 }).length))
            return 'INVALID_TYPE';
		if (this.options.disallowedExtensions && (this.options.disallowedExtensions.indexOf(data.extension) > -1 || this.options.disallowedExtensions.filter(function(val) { return val.indexOf(data.type) > -1 || val.indexOf(data.format + '/*') > -1 }).length))
			return 'INVALID_TYPE';
        if (this.options.fileMaxSize && data.size/1000000 > this.options.fileMaxSize)
            return 'MAX_FILE_SIZE';
        var v = typeof this.options.validate_file == 'function' ? this.options.validate_file(file, this.options) : true;
        if (v !== true)
            return v;
    }
    
    return true;
}

function extendDefaults(options, defaults) {
    var _ = this,
        obj = Object.assign({}, defaults, options || {});
    
    obj.files.forEach(function(item, index) {
        if (!item.type)
            item.type = _.mimeContentType(item.relative_path || item.file);
        
        item.appended = true;
    });
    
    return obj;
}

function fileFilter(req, file, cb) {
    cb(null, true);
}

function getFileAttribute(file, attribute) {
    var result = null;

    if (isset(file.data) && isset(file.data[attribute]))
        result = file.data[attribute];
    if (isset(file[attribute]))
        result = file[attribute];

    return result;
}

function generateFilename(options, file, skipReplaceCheck) {
    var conf = options.title instanceof Array ? options.title : [options.title],
        type = conf[0],
        length = isset(conf[1]) ? parseFloat(conf[1]) : 12,
        random_name = random_string(length),
        extension = file.extension,
        d = new Date(),
        result = '';
    
    switch (type) {
        case null:
        case 'auto':
            result = random_name;
            break;
        case 'name':
            result = file.title;
            break;
        default:
            var nameInfo = pathinfo(type);
            
            result = type;
            result = result.replace(/\{random\}/g, random_name);
            result = result.replace(/\{timestamp\}/g, d.getTime());
            result = result.replace(/\{date\}/g, d.getFullYear() + '-' + ('0' + (d.getMonth()+1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2) + '_' + ('0' + d.getHours()).slice(-2) + '-' + ('0' + d.getMinutes()).slice(-2) + '-' + ('0' + d.getSeconds()).slice(-2));
            result = parseVariables(result, file);
            
            
            if (!empty(nameInfo.extension)) {
                type = result.substr(0, nameInfo.title.length);
                extension = nameInfo.extension != '{extension}' ? nameInfo.extension : '';
            }
    }
    
    if (!empty(extension) && new RegExp('\.' + extension + '$').test(result) == false)
        result += '.' + extension;
    
    if (!options.replace && !skipReplaceCheck) {
        var t = file.title,
            i = 1;
        
        while (fs.existsSync(options.uploadDir + result)) {
            file.title = t + ' ('+ i +')';
            conf[0] = ['auto', 'name', '{random}'].indexOf(type) > -1 ? type : type  + ' ('+ i +')';
            result = generateFilename(options, file, true);
            i++;
        }
    }
    
    return filterFilename(result);
}

function getListInputFiles(fieldname) {
    var inputName = 'fileuploader-list-' + fieldname,
        fields = this.req.body,
        result = null;

    if (typeof this.options.listInput == 'string')
        inputName = this.options.listInput;

    if (fields && fields[inputName] && isJson(fields[inputName])) {
        var data = {
            list: [],
            values: JSON.parse(fields[inputName]),
        };

        data.values.forEach(function(value, index) {
            data.list.push(value.file); 
        });

        result = data;
    }

    return result;
}

function editFiles(data) {
    var _ = this,
        options = _.options,
        hasProperties = options.editor instanceof Object;
    
    if (options.editor === false)
        return;
    
    options.files.forEach(function(item, index) {
        var file = isset(item.relative_file) ? item.relative_file : item.file,
            fields = _.req.body;
        
        if (isset(item.listProps) && isset(item.listProps.editor))
            item.editor = item.listProps.editor;
        
        if (isset(item.uploaded) && isset(fields) && isset(fields._editorr) && isJson(fields._editorr) && _.field.input.length == 1)
            item.editor = JSON.parse(fields._editorr);
        
        if ((options.editor != null || isset(item.editor)) && fs.existsSync(file) && /^image\//.test(item.type)) {
            var width = hasProperties ? options.editor.maxWidth : null,
                height = hasProperties ? options.editor.maxHeight : null,
                quality = hasProperties ? options.editor.quality : 90,
                rotation = (isset(item.editor) ? item.editor.rotation : 0) || 0,
                crop = (isset(item.editor) ? item.editor.crop : false) || (hasProperties ? options.editor.crop : false);
            
            _.resize(file, width, height, null, crop, quality, rotation, function () {
                delete item._processing;
                if (item._processingCallback)
                    item._processingCallback();
            });
            item._processing = true;
            delete item.editor;
        }
    });
}

function sortFiles(data) {
    var _ = this,
        files = _.options.files,
        freeIndex = _.options.files.length,
        compare = function(a, b) {
            if (!isset(a.index)) {
                a.index = freeIndex;
                freeIndex++;
            }

            if (!isset(b.index)) {
                b.index = freeIndex;
                freeIndex++;
            }

            return a.index - b.index;
        }
    
    files.forEach(function(item, index) {
        if (isset(item.listProps) && isset(item.listProps.index))
            item.index = item.listProps.index;
    });
    
    if (isset(files[0]) && isset(files[0].index))
        files.sort(compare);
}

function parseVariables(text, file) {
    text = text + "";
    text = text.replace(/\{file_name\}/g, file.name);
    text = text.replace(/\{file_size\}/g, file.size);
    text = text.replace(/\{extension\}/g, file.extension);
    
    return text;
}

function random_string(length) {
    var possible = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        text = '';
    
    for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    
    return text;
}

function pathinfo(name) {
    var path = name.substr(0, name.lastIndexOf('/')),
        extension = name.indexOf('.') != -1 ? name.split('.').pop().toLowerCase() : '',
        title = name.substr(path.length + (!empty(path) ? 1 : 0), name.length - extension.length - (!empty(extension) ? 1 : 0));
    
    return {path, title, extension};
}

function filterFilename(filename) {
    var delimiter = '_',
        invalidCharacters = /["<>#%\{\}\|\\\^~\[\]`;\?:@=&\*\/]/g;
    
    filename = filename.replace(invalidCharacters, delimiter);
    filename = filename.replace(new RegExp(delimiter + '{2,}', 'g'), delimiter);

    return filename;
}

function formatSize(bytes) {
    if (bytes == 0) return '0 Byte';
    var k = 1000,
        sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));

    return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}

function unlinkTmp(file) {
    fs.unlink(file.tmp || file.path, emptyFn);
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function isset(ref) {
    return typeof ref !== 'undefined';
}

function empty(ref) {
    return (ref + "").length === 0;
}

module.exports = function() {
    return new Fileuploader(...arguments);
}
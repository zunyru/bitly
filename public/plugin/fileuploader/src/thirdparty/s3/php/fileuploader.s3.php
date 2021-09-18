<?php
# ======================================================================== #
#
#  Title      [PHP] FileUploader Amazon S3
#  Author:    innostudio.de
#  Website:   http://innostudio.de/fileuploader/
#  Version:   2.2
#  License:   https://innostudio.de/fileuploader/documentation/#license
#  Date:      23-Nov-2019
#  Purpose:   Manage files on Amazon S3 server
#
# ======================================================================== #

use Aws\S3\S3Client;
use Aws\S3\MultipartUploader;
use Aws\S3\Exception\S3Exception;
use Aws\Common\Exception\MultipartUploadException;

class FileUploader_S3 extends FileUploader {
    private $Client;
    private $Bucket;
    private $Folder;
    private $tmpDir;
    
    public function __construct($name, $options = null) {
        
        if (is_array($name))
            return $this->initClient($name);
        
        if (isset($options['auth']) && $this->initClient($options['auth'])) {
            $options['files'] = $this->listFiles($this->Folder);
            unset($options['auth']);
        }
        
        return parent::__construct($name, $options);
    }
    
    public function upload() {
        $data = parent::upload();
        
        foreach($this->options['files'] as $key=>&$item) {
            if (isset($item['uploaded']) && file_exists($item['file'])) {
                $index = array_search($item, $data['files']);

                if (!$this->getOptions()['replace']) {
                    $title = $item['title'];
                    $i = 1;
                    
                    while($this->fileExists($this->Folder . $item['name'])) {
                        $item['title'] = $title . " ({$i})";
                        $item['name'] = $item['title'] . '.' . $item['extension'];

                        $i++;
                    }
                }

                $object = $this->Folder . $item['name'];
                $result = $this->uploadFile($item['file'], $object);
                @unlink($item['file']);

                if ($result) {
                    $item['file'] = $result;
                    $item['data']['key'] = $object;

                    if ($index !== false)
                        $data[$index] = $item;
                } else {
                    unset($this->options['files'][$key]);
                    if ($index !== false)
                        unset($data[$index]);
                }
            }
            
            unset($item['listProps']['file']);
            if (empty($item['listProps']))
                unset($item['listProps']);
        }
        
        return $data;
    }
    
    protected function editFiles() {
        foreach($this->options['files'] as $key=>&$item) {
            if (!isset($item['uploaded']) && isset($item['data']['key']) && isset($item['listProps']['editor']) && strpos($item['type'], 'image/') !== false) {
                $destination = $this->options['uploadDir'] . time() . '.' . pathinfo($item['name'], PATHINFO_EXTENSION);
                $this->downloadFile($item['data']['key'], $destination);
                $item['relative_file'] = $destination;
                $item['_tempEdit'] = true;
            }
        }
        
        parent::editFiles();
        
        foreach($this->options['files'] as $key=>&$item) {
            if (isset($item['_tempEdit']) && isset($item['data']['key']) && isset($item['relative_file'])) {
                $this->uploadFile($item['relative_file'], $item['data']['key']);
                
                @unlink($item['relative_file']);
                unset($item['relative_file']);
                unset($item['_tempEdit']);
            }
        }
    }
    
    private function initClient($auth) {
        try {
            $data = array();
            
            if (isset($auth['profile'])) {
                $data['profile'] = $auth['profile'];
            } else {
                $data['credentials'] = array(
                    'key' => $auth['key'],
                    'secret' => $auth['secret'],
                );
            }
            $data['region'] = isset($auth['region']) ? $auth['region'] : 'us-east-1';
            $data['version'] = isset($auth['version']) ? $auth['version'] : 'latest';
            if (isset($auth['endpoint']))
                $data['endpoint'] = $auth['endpoint'];
            
            $this->Client = S3Client::factory($data);
            $this->Bucket = $auth['bucket'];
            $this->Folder = isset($auth['folder']) && $auth['folder'] != '/' ? rtrim($auth['folder'], '/') . '/' : '';
            
            return true;
        } catch (S3Exception $e) {}
        
        return false;
    }
    
    public function getClient() {
        return $this->Client;
    }
    
    public function createBucket($bucket, $location = 'us-east-1', $acl = 'private') {
        return $this->Client->createBucket(array(
            'Bucket' => $bucket,
            'LocationConstraint' => $location,
            'ACL' => $acl
        ));
    }
    
    public function setBucket($bucket) {
        $this->Bucket = $bucket;
    }
    
    public function bucketExists($bucket) {
        return $this->Client->doesBucketExist($bucket);
    }
    
    public function listBuckets() {
        $data = $this->Client->listBuckets();
        $result = array();

        foreach ($data['Buckets'] as $bucket) {
            $result[] = $bucket['Name'];
        }
        
        return $result;
    }
    
    public function createFolder($path, $acl = 'public-read') {
        return $this->Client->putObject(array( 
            'Bucket' => $this->Bucket,
            'Key'    => rtrim($path, '/') . '/',
            'Body'   => '',
            'ACL'    => $acl
        ));
    }
    
    public function setFolder($folder) {
        $this->Folder = $folder;
    }
    
    public function folderExists($path) {
        return $this->fileExists(rtrim($path, '/') . '/');
    }
    
    public function listFolders($path = null) {
        $data = $this->Client->listObjects([
            'Bucket' => $this->Bucket,
            'Prefix' => $path != null ? $path : $this->Folder
        ]);
        $result = array();
        
        if (isset($data['Contents'])) {
            foreach ($data['Contents'] as $object) {
                if (!$this->isDir($object['Key']))
                    continue;
                
                $result[] = basename($object['Key']);
            }
        }
        
        return $result;
    }
    
    public function deleteFolder($path) {
        return $this->deleteFile(rtrim($path, '/') . '/');
    }
    
    public function uploadFile($tmp, $file, $acl = 'public-read') {
        try {
            if (filesize($tmp) < 104857600) {
                $uploader = $this->Client->putObject(
                    array(
                        'Bucket' => $this->Bucket,
                        'Key' =>  $file,
                        'SourceFile' => $tmp,
                        'ACL' => $acl
                    )
                );
            } else {
                $multipartUploader = new MultipartUploader($this->Client, $tmp, [
                    'bucket' => $this->Bucket,
                    'key' => $file,
                    'acl' => $acl
                ]);
                
                $uploader = $multipartUploader->upload();
            }
            
            gc_collect_cycles();
            return $uploader['ObjectURL'];
        } catch (S3Exception $e) { }
        
        return false;
    }
    
    public function fileExists($file) {
        return $this->Client->doesObjectExist($this->Bucket, $file);
    }
    
    public function getFileUrl($file) {
        return $this->Client->getObjectUrl($this->Bucket, $file);
    }
    
    public function listFiles($path = null) {
        $data = $this->Client->listObjects([
            'Bucket' => $this->Bucket,
            'Prefix' => $path != null ? $path : $this->Folder
        ]);
        $result = array();
        
        if (isset($data['Contents'])) {
            foreach ($data['Contents'] as $object) {
                if ($this->isDir($object['Key']))
                    continue;
                
                $result[] = array(
                    'name' => basename($object['Key']),
                    'size' => $object['Size'],
                    'type' => self::mime_content_type($object['Key']),
                    'file' => $this->getFileUrl($object['Key']),
                    'data' => array(
                        'key' => $object['Key'],
                        'readerCrossOrigin' => 'anonymous'
                    )
                );
            }
        }
        
        return $result;
    }
    
    public function downloadFile($file, $destination) {
        return $this->Client->getObject([
            'Bucket' => $this->Bucket,
            'Key' => $file,
            'SaveAs' => $destination,
        ]);
    }
    
    public function deleteFile($file) {
        return $this->Client->deleteObject(array(
            'Bucket' => $this->Bucket,
            'Key' => $file
        ));
    }
    
    private function isDir($key) {
        return substr($key, -1) == '/';
    }
}

FileUploader::$S3 = 'FileUploader_S3';
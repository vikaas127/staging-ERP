<?php

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class Aws_client
{
    private $client;
    private $bucket;

    /**
     * Initializse S3 Client with credentials
     */
    public function __construct()
    {
        $this->client = new S3Client([
            'region' => get_option('aws_region'),
            'credentials' => [
                'key' => get_option('aws_access_key_id'),
                'secret' => get_option('aws_secret_access_key'),
            ],
        ]);

        $this->bucket = get_option('aws_bucket');
    }

    /**
     * Upload file to AWS S3.
     * 
     * @param string $filePath
     * @param string $key
     * @return string
     */
    public function upload($filePath, $key)
    {
        try {
            $result = $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SourceFile' => $filePath,
                'ACL' => 'public-read',
            ]);
            return $result['ObjectURL'];
        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Download file from AWS S3.
     * 
     * @param string $filePath
     * @return array|string
     */
    public function download($key)
    {
        try {
            $result = $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ]);
            return $result;
        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Delete file from AWS S3.
     * 
     * @param string $key
     * @return mixed
     */
    public function delete($key)
    {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ]);
        } catch (Exception $e) {
            echo "Error deleting $key: " . $e->getMessage() . "\n";
        }
    }
}
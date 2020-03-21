<?php
namespace App\Model\Utility;

use Cake\Log\Log;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class S3Manager
{

    private $_s3Client;

    /**
     * __construct method
     *
     * @return void
     */
    function __construct()
    {
        $this->_s3Client = new S3Client([
            'credentials' => [
                'key' => env('S3_ACCESS_KEY'),
                'secret' => env('S3_SECRET_KEY'),
            ],
            'region' => env('S3_REGION'),
            'version' => 'latest'
        ]);
    }

    /**
     * putObject method
     *
     * @param string $directory
     * @param string $baseFileName
     * @param string $newFileName
     * @return boolean
     */
    public function putObject($fullPath, $newFileName)
    {
        try {
            $data = $this->_s3Client->putObject([
                'ACL' => 'public-read',
                'Bucket' => env('S3_BUCKET_NAME'),
                'Key' => $newFileName,
                'SourceFile' => $fullPath,
                'ContentType' => mime_content_type($fullPath),
            ]);
        } catch (S3Exception $e) {
            Log::error($e->getMessage());
        }
        return $data['ObjectURL'];
    }

    /**
     * deleteObject method
     *
     * @param string $deleteFileName
     * @return boolean
     */
    public function deleteObject($deleteFileName)
    {
        try {
            $this->_s3Client->deleteObject([
                'Bucket' => env('S3_BUCKET_NAME'),
                'Key' => env('ITEM_IMAGE_PATH').$deleteFileName
            ]);
        } catch(S3Exception $e) {
            Log::error($e->getMessage());
        }
        return true;
    }
}
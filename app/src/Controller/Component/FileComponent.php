<?php

namespace App\Controller\Component;

use RuntimeException;

use Cake\Controller\Component;
use App\Model\Utility\S3Manager;

class FileComponent extends Component
{
    private $s3;

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->s3 = new S3Manager();

    }
    /**
     * ファイルアップロード処理
     *
     * @param array $file
     * @param string $dir
     * @return string|null
     */
    public function upload(array $file, string $dir): ?string
    {
        try {
            // ファイルがアップロードされていない
            if (!is_uploaded_file($file['tmp_name']) || $file['error'] !== 0) {
                return null;
            }
            $ext = array_search(mime_content_type($file['tmp_name']), [
                'gif' => 'image/gif',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
            ], true);

            if (!$ext) {
                throw new RuntimeException('ファイル形式が不正です。');
            }

            $filename = $dir . '/' .sha1_file($file['tmp_name']) . '.' . $ext;

            return $this->s3->putObject($file['tmp_name'], $filename);
            
        } catch (RuntimeException $e) {
            $this->Flash->error($e->getMessage());
            return null;
        }
    }
}
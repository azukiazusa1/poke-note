<?php

namespace App\Controller\Component;

use RuntimeException;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class FileComponent extends Component
{
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

            $filename = $dir . '/' . sha1_file($file['tmp_name']) . '.' . $ext;
            $path = '../webroot/img/' . $filename;

            if (!move_uploaded_file($file['tmp_name'], $path)) {
                throw new RuntimeException('ファイル保存時にエラーが発生しました。');
            }

            chmod($path, 0644);
            return $filename;
            
        } catch (RuntimeException $e) {
            $this->Flash->error($e->getMessage());
            return null;
        }
    }
}
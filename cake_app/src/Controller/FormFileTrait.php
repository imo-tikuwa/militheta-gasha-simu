<?php
namespace App\Controller;

use Cake\Core\Exception\Exception;
use Cake\Utility\Inflector;
use Intervention\Image\ImageManagerStatic;

/**
 * FormFileTrait
 *
 * ファイル項目に関するアクションをまとめたTrait
 * 表側でbootstrap-fileinput(https://github.com/kartik-v/bootstrap-fileinput)
 * を使用しているのでそちらの仕様に合わせたjsonを返す
 */
trait FormFileTrait
{
    /**
     * Ajaxファイルアップロード処理
     * @param string $input_name input[type=file]の要素名
     * @return static
     */
    public function fileUpload($input_name = null)
    {
        $error = null;
        $response_data = [];
        try {
            $this->viewBuilder()->disableAutoLayout();
            $this->autoRender = false;

            if (is_null($input_name)) {
                throw new Exception("プログラムエラーが発生しました。Invalid Request.");
            }

            // App.uploadedFilesAsObjectsの値によってオブジェクトと配列の条件分岐が必要
            $file = $this->getRequest()->getData($input_name);
            if (null !== _code('App.uploadedFilesAsObjects') && _code('App.uploadedFilesAsObjects') === false) {
                $org_name = $file['name'];
                $file_size = $file['size'];
                $tmp_name = $file['tmp_name'];
            } else {
                /** @var \Laminas\Diactoros\UploadedFile $file */
                $org_name = $file->getClientFilename();
                $file_size = $file->getSize();
                $tmp_name = $file->getStream()->getMetadata('uri');
            }

            if (empty($org_name) || empty($tmp_name)) {
                throw new Exception("プログラムエラーが発生しました。Empty File.");
            }

            $extension = pathinfo($org_name, PATHINFO_EXTENSION);
            if (empty($extension)) {
                throw new Exception("プログラムエラーが発生しました。Invalid Extension.");
            }
            $extension = strtolower($extension);

            // ファイルアップロード
            $new_image_key = sha1(uniqid(rand()));
            $cur_name = $new_image_key . "." . $extension;
            $upload_to = UPLOAD_FILE_BASE_DIR . DS . Inflector::underscore($this->name) . DS . $cur_name;
            if (!move_uploaded_file($tmp_name, $upload_to)) {
                throw new Exception("ファイルのアップロードに失敗しました。Upload Failed.");
            }

            // アップロードされたファイルが画像かつ、サムネイル生成のオプションが存在するときサムネ生成
            if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
                $thumbnail_options = _code("ThumbnailOptions.{$this->name}.{$input_name}");
                if (!empty($thumbnail_options)) {
                    $thumbnail_width = (isset($thumbnail_options['thumbnail_width']) && is_numeric($thumbnail_options['thumbnail_width'])) ? $thumbnail_options['thumbnail_width'] : null;
                    $thumbnail_height = (isset($thumbnail_options['thumbnail_height']) && is_numeric($thumbnail_options['thumbnail_height'])) ? $thumbnail_options['thumbnail_height'] : null;
                    $thumbnail_aspect_ratio_keep = (isset($thumbnail_options['thumbnail_aspect_ratio_keep']) && $thumbnail_options['thumbnail_aspect_ratio_keep'] === true) ? true : false;
                    $thumbnail_quality = (isset($thumbnail_options['thumbnail_quality']) && is_numeric($thumbnail_options['thumbnail_quality'])) ? $thumbnail_options['thumbnail_quality'] : 90;
                    $thumb_to = UPLOAD_FILE_BASE_DIR . DS . Inflector::underscore($this->name) . DS . $new_image_key . "_thumb." . $extension;
                    if ($thumbnail_aspect_ratio_keep) {
                        ImageManagerStatic::make($upload_to)->resize($thumbnail_width, $thumbnail_height, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($thumb_to, $thumbnail_quality);
                    } else {
                        ImageManagerStatic::make($upload_to)->resize($thumbnail_width, $thumbnail_height)->save($thumb_to, $thumbnail_quality);
                    }
                }
            }

            $delete_action = "";
            $prefix = $this->getRequest()->getParam('prefix');
            if (!empty($prefix)) {
                $delete_action .= "/" . Inflector::underscore($prefix);
            }
            $delete_action .= "/" . Inflector::underscore($this->name) . "/fileDelete/" . $input_name;

            $url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
            $url .= $_SERVER['SERVER_NAME'];
            if ($_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
                $url .= ":" . $_SERVER['SERVER_PORT'];
            }

            $response_data += [
                'initialPreview' => [
                    $url . "/" . UPLOAD_FILE_BASE_DIR_NAME . "/" . Inflector::underscore($this->name) . "/" . $cur_name
                ],
                'initialPreviewConfig' => [
                    0 => [
                        'caption' => $org_name,
                        'size' => $file_size,
                        'url' => $delete_action,
                        'key' => $cur_name,
                    ],
                ],
                'append' => true,
                'org_name' => $org_name,
                'cur_name' => $cur_name,
                'size' => $file_size,
                'delete_url' => $delete_action,
                'key' => $cur_name,
            ];
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $error = $e->getMessage();
        }

        if (!empty($error)) {
            $response_data['error'] = $error;
        }

        return $this->response->withType('json')->withStringBody(json_encode($response_data));
    }

    /**
     *  Ajaxファイル削除処理
     *
     * ※実際には削除しないので注意
     * 今のところbootstrap-fileinputプラグイン用の削除完了ステータスを返すだけ
     *
     * @param string $input_name input[type=file]の要素名
     * @throws Exception
     * @return static
     */
    public function fileDelete($input_name = null)
    {
        $error = null;
        $response_data = [];
        try {
            $this->viewBuilder()->disableAutoLayout();
            $this->autoRender = false;

            $key = $this->getRequest()->getData('key');
            if (is_null($key)) {
                $this->log('削除対象のファイルキーが存在しません');
                throw new Exception("プログラムエラーが発生しました。");
            }

            if (!file_exists(UPLOAD_FILE_BASE_DIR . DS . Inflector::underscore($this->name) . DS . $key)) {
                $this->log('削除対象の実ファイルが存在しません');
                throw new Exception("プログラムエラーが発生しました。");
            }

            $response_data['status'] = true;
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $error = $e->getMessage();
        }

        if (!empty($error)) {
            $response_data['error'] = $error;
        }

        return $this->response->withType('json')->withStringBody(json_encode($response_data));
    }
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * App Entity
 */
class AppEntity extends Entity
{
    /**
     * エラーメッセージの配列を取得する
     * @return array エラーメッセージの配列
     */
    public function getErrorMessages()
    {
        if (!$this->hasErrors()) {
            return null;
        }
        $error_messages = [];
        foreach ($this->getErrors() as $error) {
            $error_messages[] = $this->getEachErrorMessage($error);
        }

        return $error_messages;
    }

    /**
     * 1個辺りの項目のエラーメッセージを返す
     * @param array $each_error 1項目辺りのエラー情報
     * @return string|array
     */
    private function getEachErrorMessage($each_error)
    {
        foreach ($each_error as $error_obj) {
            if (is_array($error_obj)) {

                return $this->getEachErrorMessage($error_obj);
            } else {

                return $error_obj;
            }
        }
    }
}

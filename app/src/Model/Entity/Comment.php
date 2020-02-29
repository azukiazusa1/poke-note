<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Comment Entity
 *
 * @property int $id
 * @property string|null $body
 * @property int $article_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Article $article
 * @property \App\Model\Entity\User $user
 */
class Comment extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'body' => true,
        'article_id' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'article' => true,
        'user' => true,
    ];

    protected $_virtual = ['formated_created'];

    protected function _getFormatedCreated()
    {
        if (isset($this->_properties['created'])) {
            return $this->_properties['created']->format('Y/m/d H:i:s');
        }
    }
}

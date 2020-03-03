<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Article Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $body
 * @property bool|null $published
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\Favorite[] $favorites
 * @property \App\Model\Entity\Tag[] $tags
 */
class Article extends Entity
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
        'user_id' => true,
        'title' => true,
        'body' => true,
        'published' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'comments' => true,
        'favorites' => true,
        'tags' => true,
    ];

    protected $_virtual = ['formated_created'];

    protected function _getFormatedCreated()
    {
        if (isset($this->_properties['created'])) {
            return $this->_properties['created']->format('Y/m/d H:i:s');
        }
    }

    public function isDraft(): bool
    {
        return $this->_properties['published'] === false;
    }

    public function isAuthor(?int $id): bool
    {
        return $id === $this->_properties['user_id'];
    }
}

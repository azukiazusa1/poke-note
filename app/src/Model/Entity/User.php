<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string|null $nickname
 * @property string $email
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Article[] $articles
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\Favorite[] $favorites
 */
class User extends Entity
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
        'username' => true,
        'password' => true,
        'nickname' => true,
        'email' => true,
        'image' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'article_count' => true,
        'follow_count' => true,
        'follower_count' => true,
        'articles' => true,
        'comments' => true,
        'favorites' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];

    protected function _setPassword($value) 
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();
            return $hasher->hash($value);
        }
    }
}
